<?php 
    require_once "includes/database.php";

    $javascripts = [
        'assets/js/timeline.js'
    ];
    
    require "includes/header.php";
?>
<div class="container mt-3">
    <h1> This is an index page </h1>
    <div class="timeline-container" >
        <div id="timeline" class="timeline">
            <!-- <div class="timeline-point">
                <div class="point-header">
                    <p>Event name</p>
                    <small>2020</small>
                </div>
            </div> -->
        </div>
    </div>
    <div class="timeline-container collapse" >
        <div id="sub-timeline" class="timeline">
        </div>
    </div>
    <a href="login.php">Log in</a>
    <a class="btn btn-primary" href="#" role="button">Add event</a>
</div>
<?php require "includes/footer.php"; ?>