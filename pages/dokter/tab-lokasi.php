        <!-- Ini_Tempat-Klinik -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-center items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                        <span class="mr-2">📍</span> Tempat Klinik
                    </h3>
                </div>
                <?php 
                $status = DAO_dokter::getAlamat($profil);
                $koor = $profil->getKoor();
                $lat = (is_array($koor) && isset($koor[0])) ? $koor[0] :'';
                $long = (is_array($koor) && isset($koor[1])) ? $koor[1] :'';
                ?>
                <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                    <div class="space-y-4">
                        <label style="text-align: center;" class="block text-lg font-medium text-gray-800 mb-3">Lokasi Klinik Anda (Opsional):
                        <br><p6 style="text-align: center; font-size: 12px;">Ini akan ditampilkan di laman info profil Anda pada client-side (membutuhkan izin lokasi)</p6></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Klinik *</label>
                                <input type="text" name="nama_klinik"
                                    value="<?php echo $profil->getNamaKlinik()?: ''; ?>"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                                </div>
                            </div>
                            <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">Tandai Lokasi Klinik di Peta:</label>
                            <br><p6 style="text-align: center; font-size: 12px;">Klik dua kali untuk menandai titik di peta</p6></label>
                            
                            <div id="map-klinik" style="height: 500px; width: 100%; border-radius: 8px; border: 1px solid #961414ff;"></div>
                            <div>
                                <input type="hidden" name="latitude" id="input-latitude" value="<?php echo htmlspecialchars($lat); ?>">
                                <input type="hidden" name="longitude" id="input-longitude" value="<?php echo htmlspecialchars($long); ?>">
                            </div>
                            </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" name="update_tempat_submit"
                            class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                            💾 Update Tempat Klinik
                        </button>
                    </div>
                </form>
            </div>