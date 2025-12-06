            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6">Semua Riwayat Konsultasi</h2>
                <?php if (empty($consultations)): ?>
                    <p class="text-gray-500 text-center py-8">Belum ada data riwayat.</p>
                <?php else: ?>
                    <div class="grid gap-3">
                        <?php foreach ($consultations as $consultation): ?>
                            <div class="flex items-center justify-between p-4 bg-white hover:bg-gray-50 rounded-xl border border-gray-100 transition-colors cursor-pointer"
                                onclick="startChat('<?php echo $consultation['id']; ?>')">
                                <div class="flex items-center gap-4">
                                    <img src="<?php echo $consultation['avatar']; ?>"
                                        class="w-10 h-10 rounded-full bg-gray-100 object-cover">
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">
                                            <?php echo htmlspecialchars($consultation['patientName']); ?>
                                        </p>
                                        <p class="text-xs text-gray-500"><?php echo $consultation['fullDate']; ?> •
                                            <?php echo htmlspecialchars($consultation['petName']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">Selesai</span>
                                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>