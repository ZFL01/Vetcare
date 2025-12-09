<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../includes/DAO_others.php';
require_once __DIR__ . '/../chat-api-service/dao_chat.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isDokter = isset($_SESSION['dokter']);

$list = [];
$idChats = [];
$consultations = [];
if ($isDokter) {
    $doctorId = $_SESSION['dokter']->getId();
    $chats = DAO_chat::getAllChats(idDokter: $doctorId, now: true);
    if (!empty($chats)) {
        $list = $chats[0];
        $idChats = $chats[1];
    }
} else {
    $userId = $_SESSION['user']->getIdUser();
    $chats = DAO_chat::getAllChats(idUser: $userId);
    if (!empty($chats)) {
        $list = $chats[0];
        $idChats = $chats[1];
    }
}

if (!empty($idChats)) {
    $forms = DAO_MongoDB_Chat::getConsultationForm($idChats) ?? [];
    foreach ($forms as $f) {
        $data = $f['data'];
        $consultations[$f['idChat']] = [
            'keluhan' => $data['keluhan_gejala'] ?? ''
        ];
    }
}
?>

<section class="pt-24 pb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="font-display text-2xl md:text-3xl font-bold text-gray-900">Riwayat Chat</h1>
            <p class="text-gray-600">Lihat semua sesi konsultasi Anda.</p>
        </div>

        <?php if (empty($list)): ?>
            <div class="bg-white border border-gray-200 rounded-xl p-6 text-gray-600">Belum ada riwayat chat.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600"><?php echo $isDokter ? 'Member' : 'Dokter'; ?></th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Keluhan</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $item): ?>
                            <?php $cid = $item->getIdChat(); $keluhan = $consultations[$cid]['keluhan'] ?? ''; ?>
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-3 text-sm text-gray-800">
                                    <?php echo $isDokter ? htmlspecialchars($item->getEmail()) : htmlspecialchars($item->getNamaDokter()); ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($keluhan ?: '—'); ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if ($isDokter): ?>
                                        <a class="text-purple-600 hover:text-purple-700 font-medium" href="/pages/dokter/dokter-chat.php?chat_id=<?php echo htmlspecialchars($cid); ?>">Buka</a>
                                    <?php else: ?>
                                        <a class="text-purple-600 hover:text-purple-700 font-medium" href="?route=chat&chat_id=<?php echo htmlspecialchars($cid); ?>">Buka</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>