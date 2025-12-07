<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';
require_once __DIR__ . '/../includes/database.php';

use MongoDB\Client;

class DAO_MongoDB_Chat
{
    private static ?Client $client = null;
    private static ?MongoDB\Database $db = null;
    private static function getDb(): ?MongoDB\Database
    {
        if (self::$db !== null) {
            return self::$db;
        }

        try {
            self::$client = new Client(MONGODB_URI);
            self::$db = self::$client->selectDatabase(MONGODB_DBNAME);
            return self::$db;
        } catch (Exception $e) {
            custom_log('Gagal koneksi ke MongoDB: ' . $e->getMessage(), LOG_TYPE::ERROR);
            return null;
        }
    }

    static function insertMessage(
        string $chatId,
        string $senderId,
        string $senderRole,
        string $content
    ) {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }
        try {
            $chatsCollection = $db->selectCollection('chats');

            $newMessage = [
                'senderId' => $senderId,
                'senderRole' => $senderRole,
                'content' => $content,
                'timestamp' => new MongoDB\BSON\UTCDateTime(),
                'status' => 'sent'
            ];

            $updateRes = $chatsCollection->updateOne(
                ['_id' => $chatId],
                [
                    '$push' => ['messages' => $newMessage],
                    '$set' => ['updatedAt' => new MongoDB\BSON\UTCDateTime()]
                ]
            );
            if ($updateRes->getMatchedCount() === 0) {
                return 'Room chat tidak ditemukan';
            }
            return true;
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            custom_log('MongoDB insert error: ' . $e->getMessage(), LOG_TYPE::ERROR);
            return 'Gagal menyimpan pesan: ' . $e->getMessage();
        } catch (Exception $e) {
            custom_log("General Chat Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Terjadi kesalahan umum: " . $e->getMessage();
        }
    }

    static function getNewMessages(string $chatId, int $sinceTimeStamp)
    {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }

        try {
            $chatsCollection = $db->selectCollection('chats');

            $since = new DateTime();
            $since->setTimestamp($sinceTimeStamp);
            $sinceBsonDate = new MongoDB\BSON\UTCDateTime($since->getTimestamp() * 1000);

            $pipeline = [
                ['$match' => ['_id' => $chatId]],
                ['$unwind' => '$messages'],
                ['$match' => ['messages.timestamp' => ['$gt' => $sinceBsonDate]]],
                ['$sort' => ['messages.timestamp' => 1]],
                ['$replaceRoot' => ['newRoot' => '$messages']]
            ];

            $cursor = $chatsCollection->aggregate($pipeline);
            $messages = $cursor->toArray();

            // Format ulang ObjectId dan UTCDateTime ke string untuk respons JSON yang mudah dibaca JS
            $formattedMessages = array_map(function ($msg) {
                // Konversi ObjectId ke string
                $senderId = (string) $msg['senderId'];
                if ($msg['senderRole'] === 'dokter') {
                    // 2. [PENAMBAHAN] Hash ID DOKTER
                    // Jika peran adalah dokter, ganti ID aslinya dengan Hash ID
                    $msg['senderId'] = hashId($senderId, true);
                } else {
                    // Jika peran adalah user/client, biarkan ID-nya tetap ID aslinya (atau bisa juga di-hash jika diperlukan)
                    $msg['senderId'] = $senderId;
                }
                // Konversi UTCDateTime ke string ISO 8601
                $msg['timestamp'] = $msg['timestamp']->toDateTime()->format(DateTime::ISO8601);
                return $msg;
            }, $messages);

            return $formattedMessages;

        } catch (\MongoDB\Driver\Exception\Exception $e) {
            custom_log("MongoDB Retrieve Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Gagal mengambil pesan: " . $e->getMessage();
        } catch (Exception $e) {
            custom_log("General Chat Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Terjadi kesalahan umum saat Polling: " . $e->getMessage();
        }
    }

    static function findChatRoom(string $mysqlChatId)
    {
        $db = self::getDb();
        if ($db === null) {
            return null;
        }
        try {
            $chatsCollection = $db->selectCollection('chats');
            // Mencari berdasarkan kolom 'mysqlId' yang akan kita buat di createChatRoom
            $document = $chatsCollection->findOne(['_id' => $mysqlChatId]);
            return $document;
        } catch (Exception $e) {
            custom_log("MongoDB findChatRoom Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return null;
        }
    }

    static function createChatRoom(string $mysqlChatId)
    {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }
        try {
            $chatsCollection = $db->selectCollection('chats');

            $document = [
                // Simpan ID dari MySQL agar dapat dicari oleh findChatRoom
                '_id' => $mysqlChatId,
                // Inisialisasi array pesan kosong
                'messages' => [],
                'createdAt' => new MongoDB\BSON\UTCDateTime(),
                'updatedAt' => new MongoDB\BSON\UTCDateTime(),
            ];

            $result = $chatsCollection->insertOne($document);

            if ($result->getInsertedCount() === 1) {
                // Kembalikan ObjectId yang baru dibuat (sebagai string)
                return (string) $result->getInsertedId();
            }
            return 'Gagal memasukkan dokumen chat baru.';

        } catch (\MongoDB\Driver\Exception\Exception $e) {
            custom_log('MongoDB create error: ' . $e->getMessage(), LOG_TYPE::ERROR);
            return 'Gagal membuat room chat: ' . $e->getMessage();
        } catch (Exception $e) {
            custom_log("General Chat Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Terjadi kesalahan umum: " . $e->getMessage();
        }
    }

    static function insertConsultationForm(string $chatId, array $formData)
    {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }
        try {
            $formsCollection = $db->selectCollection('Konsultasi_forms');
            $document = [
                '_id' => $chatId,
                'chatId' => $chatId,
                'timestamp' => new MongoDB\BSON\UTCDateTime(),
                'formData' => $formData,
            ];
            $result = $formsCollection->insertOne($document);
            if ($result->getInsertedCount() === 1) {
                return true;
            } else {
                return 'Gagal menyimpan formulir.';
            }
        } catch (\Exception $e) {
            custom_log("MongoDB Form Insert Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Gagal menyimpan formulir: " . $e->getMessage();
        }
    }

    static function getConsultationForm(array $chatId)
    {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }

        try {
            $formsCollection = $db->selectCollection('Konsultasi_forms');
            $filter = [
                '_id' => [
                    '$in' => $chatId
                ]
            ];
            $cursor = $formsCollection->find($filter);
            $documents = $cursor->toArray();

            return self::processedForms($documents);
        } catch (\Exception $e) {
            custom_log("MongoDB Form getConsultationForm Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Gagal mengambil formulir: " . $e->getMessage();
        }
    }

    static function processedForms(array $documents)
    {
        $processedForms = [];
        foreach ($documents as $doc) {
            $idChat = $doc['chatId'];
            $data = $doc['formData'];

            $processedForms[] = [
                'idChat' => $idChat,
                'data' => $data,
            ];
        }
        return $processedForms;
    }

    static function getChatForm(string $chatId)
    {
        $db = self::getDb();
        if ($db === null) {
            return 'Koneksi gagal';
        }
        try {
            $formsCollection = $db->selectCollection('Konsultasi_forms');
            $options = [
                'typeMap' => [
                    'root' => 'array',
                    'document' => 'array',
                    'array' => 'array',
                ]
            ];
            $document = $formsCollection->findOne(['_id' => $chatId], $options);

            if ($document && isset($document['timestamp']) && $document['timestamp'] instanceof MongoDB\BSON\UTCDateTime) {
                $timestampMs = $document['timestamp']->toDateTime()->getTimestamp();
                $document['timestamp'] = date('Y-m-d H:i:s', $timestampMs);
            }

            return $document;
        } catch (\Exception $e) {
            custom_log("MongoDB Form getChatForm Error: " . $e->getMessage(), LOG_TYPE::ERROR);
            return "Gagal mengambil formulir: " . $e->getMessage();
        }
    }

}
?>