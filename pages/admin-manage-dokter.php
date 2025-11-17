<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/DAO_dokter.php';

// Check if user is admin
// if (!isset($_SESSION['user']) || $_SESSION['user']->getRole() !== 'Admin') {
//     header('Location: ?route=auth');
//     exit();
// }

$message = '';
$messageType = '';

// Handle approval submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $id_dokter = (int)$_POST['id_dokter'];
        $action = $_POST['action'];
        
        try {
            $conn = Database::getConnection();
            
            if ($action === 'approve') {
                $exp_strv = $_POST['exp_strv'];
                $exp_sip = $_POST['exp_sip'];

                if (empty($exp_strv) || empty($exp_sip)) {
                    $message = "❌ Harap isi kedua tanggal kedaluwarsa (Exp. STRV dan Exp. SIP).";
                    $messageType = "error";
                } elseif (new DateTime($exp_strv) < new DateTime('today') || new DateTime($exp_sip) < new DateTime('today')) {
                    // Tambahkan validasi tanggal
                    $message = "❌ Tanggal kedaluwarsa tidak boleh lebih awal dari hari ini.";
                    $messageType = "error";
                } else {
                    $query = "UPDATE m_dokter SET status = 'aktif', exp_strv = ?, exp_sip = ? WHERE id_dokter = ?";
                    
                    $stmt = $conn->prepare($query);
                    $result = $stmt->execute([$exp_strv, $exp_sip, $id_dokter]);
                    
                    if ($result) {
                        $message = "✅ Dokter berhasil di-approve, data EXP disimpan, dan status diubah menjadi aktif!";
                        $messageType = "success";
                    } else {
                        $message = "❌ Gagal meng-approve dokter";
                        $messageType = "error";
                    }
                }
            } elseif ($action === 'reject') {
                $reason = $_POST['rejection_reason'] ?? '';
                
                $query = "UPDATE m_dokter SET status = 'nonaktif' WHERE id_dokter = ?";
                
                $stmt = $conn->prepare($query);
                $result = $stmt->execute([$id_dokter]);
                
                if ($result) {
                    $message = "❌ Dokter ditolak dan status tetap nonaktif";
                    $messageType = "warning";
                } else {
                    $message = "Gagal menolak dokter";
                    $messageType = "error";
                }
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// Get all dokter pending approval (status = 'nonaktif')
try {
    $conn = Database::getConnection();
    
    // Get pending dokter (those with status='nonaktif')
    $query = "SELECT d.id_dokter, d.nama_dokter, d.ttl, d.strv, d.exp_strv, 
                     d.sip, d.exp_sip, d.pengalaman, d.status,
                     p.email, p.created as tgl_daftar
              FROM m_dokter d
              LEFT JOIN m_pengguna p ON d.id_dokter = p.id_pengguna
              WHERE d.status = 'nonaktif'
              ORDER BY p.created DESC";
    
    $stmt = $conn->query($query);
    $pending_dokters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get kategori untuk setiap dokter
    $all_kategori = [];
    
} catch (PDOException $e) {
    $message = "Error fetching data: " . $e->getMessage();
    $messageType = "error";
    $pending_dokters = [];
    $all_kategori = [];
}
?>

<?php require_once __DIR__ . '/../header.php'; ?>

<main class="pb-20 bg-gradient-to-b from-white via-purple-50 to-white min-h-[80vh]">
  <div class="container mx-auto px-6 max-w-7xl">
    
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">📋 Manajemen Dokter</h1>
      <p class="text-gray-600">Verifikasi dan approve pendaftaran dokter baru</p>
    </div>

    <!-- Message Alert -->
    <?php if ($message): ?>
      <div class="mb-6 p-4 rounded-lg text-white <?php 
        echo $messageType === 'success' ? 'bg-green-500' : 
             ($messageType === 'warning' ? 'bg-yellow-500' : 'bg-red-500'); 
      ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200">
      <button class="px-6 py-3 font-semibold text-purple-600 border-b-2 border-purple-600">
        Menunggu Approval (<?php echo count($pending_dokters); ?>)
      </button>
    </div>

    <!-- Pending Dokter List -->
    <div class="space-y-6">
      <?php if (empty($pending_dokters)): ?>
        <div class="bg-white rounded-lg p-8 text-center text-gray-600">
          <p class="text-lg">Tidak ada dokter yang menunggu verifikasi</p>
        </div>
      <?php else: ?>
        <?php foreach ($pending_dokters as $dokter): ?>
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100">
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($dokter['nama_dokter']); ?></h3>
                  <p class="text-gray-600">📧 Email: <?php echo htmlspecialchars($dokter['email']); ?></p>
                </div>
                <div class="text-right">
                    <?php if (!empty($dokter['strv']) || !empty($dokter['sip'])): ?>
                        <div class="inline-block">
                            <?php if (!empty($dokter['strv'])): ?>
                                  <a href="public/docs/dokter/strv/<?php echo htmlspecialchars($dokter['strv']); ?>" target="_blank" class="inline-flex items-center px-4 py-2 rounded-full text-white bg-blue-500 hover:bg-blue-600 mr-2">Lihat STRV</a>
                            <?php endif; ?>
                            <?php if (!empty($dokter['sip'])): ?>
                                <a href="public/docs/dokter/sip/<?php echo htmlspecialchars($dokter['sip']); ?>" target="_blank" class="inline-flex items-center px-4 py-2 rounded-full text-white bg-blue-500 hover:bg-blue-600">Lihat SIP</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="inline-block px-4 py-2 rounded-full text-gray-600 bg-gray-200">Tidak ada dokumen</span>
                    <?php endif; ?>
                  <p class="text-gray-600 text-sm mt-2">Terdaftar: <?php echo date('d/m/Y H:i', strtotime($dokter['tgl_daftar'])); ?></p>
                </div>
              </div>


              <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                <div>
                  <p><strong>TTL:</strong> <?php echo htmlspecialchars($dokter['ttl']); ?></p>
                  <p><strong>Tahun awal mulai praktik:</strong> <?php echo htmlspecialchars($dokter['pengalaman']); ?></p>
                </div>
                <div>
                  <p><strong>Exp. STRV:</strong> <?php echo htmlspecialchars($dokter['exp_strv'] ?? ''); ?></p>
                  <p><strong>Exp. SIP:</strong> <?php echo htmlspecialchars($dokter['exp_sip'] ?? ''); ?></p>
                </div>
              </div>
            </div>

            <!-- Approval Form -->
            <div class="p-6 bg-gray-50">
              <form method="POST" class="space-y-4">
                <input type="hidden" name="id_dokter" value="<?php echo $dokter['id_dokter']; ?>">
                
                <p class="text-gray-700 font-semibold mb-4">Pilih aksi untuk dokter ini:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="exp_strv_<?php echo $dokter['id_dokter']; ?>" class="block text-sm font-medium text-gray-700">Exp. STRV</label>
                        <input type="date" id="exp_strv_<?php echo $dokter['id_dokter']; ?>" name="exp_strv" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label for="exp_sip_<?php echo $dokter['id_dokter']; ?>" class="block text-sm font-medium text-gray-700">Exp. SIP</label>
                        <input type="date" id="exp_sip_<?php echo $dokter['id_dokter']; ?>" name="exp_sip" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" required>
                    </div>
                </div>
                
                <div class="flex gap-3">
                  <button type="submit" name="action" value="approve" 
                          class="flex-1 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    ✅ Approve - Aktifkan Dokter
                  </button>
                  <button type="button" onclick="showRejectForm(<?php echo $dokter['id_dokter']; ?>)" 
                          class="flex-1 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    ❌ Tolak Dokter
                  </button>
                </div>
              </form>

              <!-- Hidden Reject Form -->
              <form method="POST" id="reject-form-<?php echo $dokter['id_dokter']; ?>" class="hidden space-y-4 mt-4 pt-4 border-t border-gray-200">
                <input type="hidden" name="id_dokter" value="<?php echo $dokter['id_dokter']; ?>">
                <input type="hidden" name="action" value="reject">
                
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Alasan Penolakan
                  </label>
                  <textarea name="rejection_reason" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                            placeholder="Jelaskan alasan penolakan (opsional)..."></textarea>
                </div>

                <div class="flex gap-3">
                  <button type="submit" 
                          class="flex-1 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    ✅ Konfirmasi Penolakan
                  </button>
                  <button type="button" onclick="hideRejectForm(<?php echo $dokter['id_dokter']; ?>)" 
                          class="flex-1 bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    ❌ Batal
                  </button>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</main>

<script>
function showRejectForm(id) {
  document.getElementById('reject-form-' + id).classList.remove('hidden');
}

function hideRejectForm(id) {
  document.getElementById('reject-form-' + id).classList.add('hidden');
}
</script>

<style>
  .shadow-card { box-shadow: 0 10px 30px rgba(150, 100, 200, 0.08); }
</style>

<?php require_once __DIR__ . '/../footer.php'; ?>
