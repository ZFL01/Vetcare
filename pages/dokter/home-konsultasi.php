<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6">Semua Riwayat Konsultasi</h2>
    <?php if (empty($allChats)): ?>
        <p class="text-gray-500 text-center py-8">Belum ada data riwayat.</p>
    <?php else: ?>
        <div class="grid gap-3">
            <?php foreach ($allChats as $chat):
                $idChat = $chat->getIdChat();
                $detail = $consultations[$idChat] ?? [];
                ?>
                <div class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 rounded-xl border border-gray-100 transition-colors cursor-pointer"
                    onclick="startChat('<?php echo $idChat; ?>')">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">
                                <?php echo htmlspecialchars($chat->getEmail()); ?>
                            </p>
                            <p class="text-xs text-gray-500"><?php echo $chat->getWaktuMulai(); ?> •
                                <?php echo htmlspecialchars($detail['nama_hewan'] ?? 'Hewan'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>