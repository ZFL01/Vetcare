const express = require('express');
const { MongoClient, ServerApiVersion, ObjectId } = require('mongodb');
const cors = require('cors');

const uri = "mongodb+srv://klinikH:QZ6Bqc8HAH5LPa7g@cluster0.rgxz9ub.mongodb.net/?appName=Cluster0";

// Create a MongoClient with a MongoClientOptions object to set the Stable API version
const client = new MongoClient(uri, {
  serverApi: {
    version: ServerApiVersion.v1,
    strict: true,
    deprecationErrors: true,
  }
});

let dbConnection;
async function getDbConnection() {
  if (dbConnection) {
    return dbConnection;
  }
  try {
    // Connect the client to the server	(optional starting in v4.7)
    await client.connect();
    dbConnection = client.db("klinikH");
    console.log("Connected to MongoDB!");
    // Send a ping to confirm a successful connection
    await client.db("admin").command({ ping: 1 });
    return dbConnection;
  } catch (err) {
    console.error("Failed to connect to MongoDB", err);
    proccess.exit(1);
  }
}

const app = express();
const port = 3000;

const corsOptions = {
  origin: '*',
  methds: ['GET', 'POST'],
  allowedHeaders: ['Content-Type'],
};
app.use(cors(corsOptions));
app.use(express.json());

// Controller untuk pesan chat
async function handleSendMessage(req, res) {
  const db = await getDbConnection();
  if (!db) {
    return res.status(500).json({ message: 'Database connection error' });
  }

  const ChatID = req.params.chatId;
  const { senderId, senderRole, content } = req.body;
  if (!ChatID || !senderId || !senderRole || !content) {
    return res.status(400).json({ message: 'Data pengirim tidak lengkap!' });
  }

  try {
    const { objekId } = require('mongodb');
    const newMessage = {
      senderId: new objekId(senderId),
      senderRole: senderRole,
      content: content,
      timestamp: new Date(),
      status: 'sent'
    };

    const messagesCollection = db.collection('messages');
    const updateResult = await messagesCollection.updateOne(
      { _id: new objekId(ChatID) },
      {
        $push: { messages: newMessage },
        $set: { updateAt: new Date() }
      }
    );

    if (updateResult.matchedCount === 0) {
      return res.status(404).json({ message: 'Chat room not found' });
    }

    res.status(200).json({ message: 'Message sent successfully', sentMessage: newMessage });
  } catch (err) {
    console.error("Error sending message:", err);
    res.status(500).json({ message: 'Failed to send message' });
  }
}

async function handleGetNewMessages(req, res) {
  const db = await connectToMongoDB();
  if (!db) {
    return res.status(503).json({ error: "Layanan database tidak tersedia" });
  }

  const chatId = req.params.chatId;
  const sinceTimestamp = req.query.since || new Date(0).toISOString(); // Default: dari awal waktu

  try {
    const chatsCollection = db.collection('chats');

    // Aggregation Pipeline untuk memfilter pesan di dalam array sub-dokumen
    const result = await chatsCollection.aggregate([
      { $match: { _id: new ObjectId(chatId) } },
      // Flatten array messages menjadi dokumen terpisah
      { $unwind: "$messages" },
      // Filter pesan yang lebih baru dari timestamp yang diberikan
      { $match: { "messages.timestamp": { $gt: sinceTimestamp } } },
      // Urutkan berdasarkan timestamp
      { $sort: { "messages.timestamp": 1 } },
      // Proyeksikan hanya objek pesan itu sendiri
      { $replaceRoot: { newRoot: "$messages" } }
    ]).toArray();

    // Kembalikan array pesan baru
    res.status(200).json(result);

  } catch (error) {
    console.error(`Error saat mengambil pesan baru dari Chat ID ${chatId}:`, error);
    res.status(500).json({ error: "Gagal mengambil pesan baru", detail: error.message });
  }
}

//route untuk mengirim pesan chat
app.post('/api/chats/:chatId/send', handleSendMessage);

// Rute untuk Polling pesan baru (Controller: handleGetNewMessages)
app.get('/api/chats/:chatId/messages/new', handleGetNewMessages);

// Rute sederhana untuk mengecek kesehatan server
app.get('/', (req, res) => {
  res.send('Chat API Service is running.');
});

// Mulai server setelah koneksi database berhasil

getDbConnection().then(() => {
  app.listen(port, () => {
    console.log(`server Express berjalan di port ${port}`);
  });
}).catch(err => {
  console.error("Gagal memulai server:", err);
});
