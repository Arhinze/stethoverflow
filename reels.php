<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/views/Index_Segments.php");
//Index_Segments::header();

$reels_stmt = $pdo->prepare("SELECT * FROM videos ORDER BY video_id DESC LIMIT ?, ?");
$reels_stmt->execute([0,100]);
$reels_data = $reels_stmt->fetchAll(PDO::FETCH_OBJ);

?>

<div class="main_body">
    <div class="reels-container">
    <?php foreach($reels_data as $rd) { ?>
        <div class="reel-video-wrapper">
            <video class="medical-video" loop muted playsinline>
                <source src="/static/videos/<?=$rd->video_url?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            
            <div class="video-overlay">
                <h3>@StethOverflow</h3>
                <p><?php htmlspecialchars($rd->description) ?></p>
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

<?php //Index_Segments::footer(); ?>