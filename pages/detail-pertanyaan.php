<?php
/**
 * File: pages/detail-pertanyaan.php
 * Halaman detail pertanyaan beserta jawabannya
 */

$pageTitle = "Detail Pertanyaan - VetCare";
require_once __DIR__ . '/../header.php';
require_once __DIR__ . '../includes/DAO_others.php';

// Get ID pertanyaan
if (!isset($_GET['id'])) {
    setFlash('error', 'ID pertanyaan tidak ditemukan!');
    header('Location: ' . $_SERVER['PHP_SELF'] . '?route=tanya-dokter');
    exit();
}
// Get pertanyaan
$objTanya = DAO_Tanya::showAnswer($_GET['id']);
if (!$objTanya) {
    setFlash('error', 'Pertanyaan tidak ditemukan!');
    header('Location: ' . $_SERVER['PHP_SELF'] . '?route=err');
    exit();
}
?>

<style>
.detail-container {
    max-width: 900px;
    margin: 0 auto;
}

.back-button {
    background: white;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    color: #667eea;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s;
}

.back-button:hover {
    transform: translateX(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.question-detail {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.question-header-section {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.question-meta-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #667eea;
}

.user-info h3 {
    color: #333;
    margin-bottom: 5px;
}

.user-info p {
    color: #999;
    font-size: 14px;
}

.status-badge {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.status-baru {
    background: #ffe0e0;
    color: #ff6b6b;
}

.status-dijawab {
    background: #d3f9d8;
    color: #2b8a3e;
}

.question-title-main {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.3;
}

.question-content-main {
    font-size: 16px;
    line-height: 1.7;
    color: #555;
    margin-bottom: 20px;
}

.question-meta-details {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 14px;
    color: #777;
}

.kategori-badge {
    background: #e9ecef;
    color: #495057;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 500;
}

.time-info {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Jawaban Section */
.answers-section {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

.answers-header {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.no-answers {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.no-answers-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.answer-item {
    border: 1px solid #e0e0e0;
    padding: 25px;
    border-radius: 15px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.answer-item:hover {
    border-color: #667eea;
    box-shadow: 0 3px 10px rgba(102, 126, 234, 0.1);
}

.answer-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.dokter-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #667eea;
}

.dokter-info h4 {
    color: #333;
    margin-bottom: 3px;
    font-size: 16px;
}

.dokter-info p {
    color: #777;
    font-size: 14px;
    margin: 0;
}

.answer-content {
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 15px;
}

.answer-time {
    color: #999;
    font-size: 13px;
    font-style: italic;
}
</style>

<div class="container">
    <div class="detail-container">
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?route=tanya-dokter" class="back-button">
            ← Kembali ke Daftar Pertanyaan
        </a>

        <div class="question-detail">
            <div class="question-header-section">
                <div class="question-meta-info">
                    <img src="<?php echo BASE_URL; ?>public/images/dokter/default-profile.jpg"
                         class="user-avatar"
                         alt="User Avatar">
                    <div class="user-info">
                        <h3><?php echo htmlspecialchars($objTanya->getUser()); ?></h3>
                        <p>Pemilik Hewan</p>
                    </div>
                </div>
                <span class="status-badge status-<?php echo $objTanya->getStatus(); ?>">
                    <?php
                    $status_text = [
                        'menunggu' => 'Belum Dijawab',
                        'terjawab' => 'Terjawab',
                    ];
                    echo $status_text[$objTanya->getStatus()];
                    ?>
                </span>
            </div>

            <h1 class="question-title-main"><?php echo htmlspecialchars($objTanya->getJudul()); ?></h1>

            <div class="question-content-main">
                <?php echo nl2br(htmlspecialchars($objTanya->getDeskripsi())); ?>
            </div>

            <div class="question-meta-details">
                <span class="kategori-badge"><?php echo ucfirst($objTanya->getTag()); ?></span>
                <span class="time-info">
                    📅 <?php echo formatTanggal($objTanya->getCreated()); ?>
                </span>
            </div>
        </div>

        <!-- JAWABAN -->
        <div class="answers-section">
            <h2 class="answers-header">💬 Jawaban</h2>

            <?php if (empty($objTanya->getJawaban())): ?>
                <div class="no-answers">
                    <div class="no-answers-icon">📝</div>
                    <p>Belum ada jawaban untuk pertanyaan ini</p>
                </div>
            <?php else: ?>
                    <div class="answer-item">
                        <div class="answer-header">
                            <img src="<?php echo BASE_URL; ?>public/images/dokter/default-profile.jpg">
                                 class="dokter-avatar"
                                 alt="Dokter Avatar">
                            <div class="dokter-info">
                                <h4><?php echo htmlspecialchars($objTanya->getDokter()); ?></h4>
                            </div>
                        </div>

                        <div class="answer-content">
                            <?php echo nl2br(htmlspecialchars($objTanya->getJawaban())); ?>
                        </div>

                        <div class="answer-time">
                            ⏰ Dijawab <?php echo timeAgo($objTanya->getTglJawab()); ?>
                        </div>
                    </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../footer-dokter.php'; ?>
