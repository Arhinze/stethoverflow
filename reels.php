<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
Index_Segments::reels_header();

$reels_stmt = $pdo->prepare("SELECT * FROM videos ORDER BY video_id DESC LIMIT ?, ?");
$reels_stmt->execute([0,100]);
$reels_data = $reels_stmt->fetchAll(PDO::FETCH_OBJ);

?>

<div class="reels_body">
    <div class="reels-container">
    <?php foreach($reels_data as $rd) { ?>
        <div class="reel-video-wrapper">
            <a href="javascript:history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>

            <video class="medical-video" loop muted playsinline>
                <source src="uploads/videos/<?= $rd->video_url ?>" type="video/mp4">
            </video>

            <div class="status-icon"><i class="fas fa-play"></i></div>

            <div class="side-actions">
                <div class="action-btn"><i class="fas fa-heart"></i><span>1.2k</span></div>
                <div class="action-btn"><i class="fas fa-comment"></i><span>45</span></div>
                <div class="action-btn"><i class="fas fa-retweet"></i></div>
                <div class="action-btn"><i class="fas fa-share"></i></div>
            </div>

            <div class="video-overlay">
                <div class="bottom-info">
                    <h3>@StethOverflow</h3>
                    <p><?= $rd->description ?></p>
                </div>
                <div class="mute-toggle"><i class="fas fa-volume-mute"></i></div>
            </div>

            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<div><!-- script for reels section -->
    <script>
        const options = {
            root: null, // Use the viewport
            threshold: 0.7 // Trigger when 70% of the video is visible
        };
    
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const video = entry.target;
                if (entry.isIntersecting) {
                    video.play();
                    video.muted = false; // Optional: Unmute on view
                } else {
                    video.pause();
                    video.currentTime = 0; // Reset video when scrolled away
                }
            });
        }, options);
    
        // Attach observer to all medical videos
        document.querySelectorAll('.medical-video').forEach(vid => {
            observer.observe(vid);
        });
    
        // Simple click-to-pause toggle
        document.querySelectorAll('.medical-video').forEach(vid => {
            vid.addEventListener('click', () => {
                if (vid.paused) vid.play();
                else vid.pause();
            });
        });
    </script>
</div>

</body>
</html>
<?php //Index_Segments::footer(); ?>