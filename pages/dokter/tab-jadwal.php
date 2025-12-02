<!-- Ini_Jadwal -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📅 Sesuaikan Jadwal Praktik</h3>
                
                <form id="scheduleForm" method="POST" action=""> 
                    <input type="hidden" name="action" value="update_full_schedule">
                    
                    <div id="scheduleContainer" class="space-y-6">
                        
                        <?php
                        foreach ($allDaysMap as $dayIndex => $dayName):
                            $isScheduled = isset($dokterJadwal[$dayName]);
                            $sesiList = $isScheduled ? $dokterJadwal[$dayName] : [];
                        ?>
                        
                        <div class="border rounded-xl p-4 <?= $isScheduled ? 'border-purple-400 bg-purple-50' : 'border-gray-300' ?>" 
                            data-day-index="<?= $dayIndex ?>" data-day-name="<?= $dayName ?>">
                            
                            <div class="flex justify-between items-center mb-3 border-b pb-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="days_active[]" 
                                        value="<?= $dayIndex ?>"
                                        <?= $isScheduled ? 'checked' : '' ?>
                                        class="h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500 day-toggle"
                                    >
                                    <span class="ml-3 text-xl font-bold text-gray-800"><?= htmlspecialchars($dayName) ?></span>
                                </label>
                                <button type="button" 
                                    class="text-sm py-1 px-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition" 
                                    onclick="addSlot(this, '<?= $dayIndex ?>')">
                                    + Tambah Sesi
                                </button>
                            </div>

                            <div class="space-y-2 schedule-slots-list" id="slots-<?= $dayIndex ?>">

                                <?php 
                                // Loop 2: Tampilkan Sesi yang Sudah Ada
                                foreach ($sesiList as $i => $sesi):
                                    // Indeks $i digunakan untuk array name di JS
                                ?>
                                <div class="grid grid-cols-4 gap-2 items-center slot-row" data-slot-id="<?= $i ?>">
                                    <span class="col-span-1">Buka</span>
                                    <input type="time" 
                                        name="schedule[<?= $dayIndex ?>][<?= $i ?>][buka]" 
                                        value="<?= $sesi->getBuka() ?>" 
                                        required
                                        class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                                    
                                    <span class="col-span-1">Tutup</span>
                                    <input type="time" 
                                        name="schedule[<?= $dayIndex ?>][<?= $i ?>][tutup]" 
                                        value="<?= $sesi->getTutup() ?>" 
                                        required
                                        class="col-span-1 border border-gray-300 rounded-lg p-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                                    
                                    <button type="button" 
                                        class="col-span-2 text-sm text-red-600 hover:text-red-800 flex items-center justify-start gap-1"
                                        onclick="removeSlot(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4"></path></svg>
                                        Hapus
                                    </button>
                                </div>
                                <?php endforeach; ?>
                                
                            </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <button type="submit" name="update_jadwal_submit"
                            class="bg-purple-600 text-white py-2 px-6 rounded-lg hover:bg-purple-700 transition-colors">
                            💾 Simpan Semua Jadwal
                        </button>
                    </div>
                </form>
            </div>