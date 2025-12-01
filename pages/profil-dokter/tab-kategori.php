<!-- Ini_Kategori -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-center items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                        <span class="mr-2">📊</span> kategori
                    </h3>
                </div>
                <form method="POST" action="" class="space-y-6">
                    <input type="hidden" name="action" value="update_kategori">
                    <div class="space-y-4">
                        <label class="block text-lg font-medium text-gray-800 mb-3">Pilih Spesialisasi Anda:</label>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                            <?php
                            $allK = DAO_kategori::getAllKategori();
                            $profilKategori = $profil->getKategori();
                            $profilKategoriIds = array_column($profilKategori, 'idK');

                            foreach ($allK as $kItem):
                                $curId = $kItem->getIdK();
                                $isChecked = in_array($curId, $profilKategoriIds) ? 'checked': '';
                                ?>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="kategori_ids[]" value="<?php echo $curId; ?>"
                                        <?php echo $isChecked; ?>
                                        class="h-5 w-5 text-primary rounded border-gray-300 focus:ring-primary">
                                    <span
                                        class="ml-2 text-gray-700"><?php echo htmlspecialchars($kItem->getNamaKateg()); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-sm text-gray-500 mt-4">Centang kategori yang sesuai dengan spesialisasi praktik
                            Anda.</p>
                    </div>
                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" name="update_kategori_submit"
                            class="bg-primary text-white py-2 px-6 rounded-lg hover:bg-secondary transition-colors">
                            💾 Update Kategori
                        </button>
                    </div>
                </form>
            </div>