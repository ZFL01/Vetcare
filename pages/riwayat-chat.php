<?php

require_once __DIR__ . '/../chat-api-service/controller_chat.php';

$semuaChat = [];
$semuaKonsultasi = [];

$semuaId = [];
$userId = $_SESSION['user']->getIdUser();

$pesan = DAO_chat::getAllChats(idUser: $userId);
if (!empty($pesan)) {
    $semuaChat = $pesan[0];
    $semuaId = $pesan[1];
    $hasil = DAO_MongoDB_Chat::getConsultationForm($semuaId) ?? [];

    foreach ($hasil as $item) {
        $formData = $item['data'];
        $semuaKonsultasi[$item['idChat']] = [
            'nama_hewan' => $formData['nama_hewan'],
            'jenis_hewan' => $formData['jenis_hewan'],
            'usia_hewan' => $formData['usia_hewan'],
            'keluhan' => $formData['keluhan_gejala'],
        ];
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Styling Navigasi Kapsul (Pill) - FIX: display inline-flex */
    .pill-container {
        display: inline-flex;
        background-color: #F3F4F6;
        padding: 0.375rem;
        border-radius: 9999px;
        white-space: nowrap;
        /* Mencegah turun ke bawah */
    }

    .pill-item {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        color: #6B7280;
        background-color: transparent;
        border: none;
    }

    .pill-item.active {
        background-color: white;
        color: #111827;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        font-weight: 700;
    }

    .pill-item:hover:not(.active) {
        color: #374151;
    }

    /* Scrollbar Halus */
    .custom-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="min-h-screen bg-[#F9FAFB] pt-24 font-sans">
    
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6">Semua Riwayat Konsultasi</h2>
    <?php if (empty($semuaKonsultasi)): ?>
        <p class="text-gray-500 text-center py-8">Belum ada data riwayat.</p>
    <?php else: ?>
        <div class="grid gap-3">
            <?php foreach ($semuaChat as $msg):
                $idPesan = $msg->getIdChat();
                $detail = $semuaKonsultasi[$idPesan];?>
                <div class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 rounded-xl border border-gray-100 transition-colors cursor-pointer"
                    onclick="startChat('<?php echo $idPesan; ?>')">
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="font-bold text-gray-900 text-sm">
                                <?php echo htmlspecialchars($msg->getNamaDokter()); ?>
                            </p>
                            <p class="text-xs text-gray-500"><?php echo $msg->getWaktuMulai(); ?> •
                                <?php echo htmlspecialchars($detail['nama_hewan']); ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Selesai</span>
                        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function startChat(chatId) {
        window.location.href = '/?route=chat&chat_id=' + chatId;
    }
</script>