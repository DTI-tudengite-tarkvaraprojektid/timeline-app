<?php 
    require_once "includes/database.php";

    $javascripts = [
        'assets/js/timeline.js'
    ];
    
    require "includes/header.php";
?>
<div class="container mt-3">
    <h3> [Insert timeline name here] </h3>
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
    <div class="row">
        <div class="col">
            <div id="card-event" class="card" style="display: none;">
                <div class="card-header">
                    None
                </div>
                <div class="card-body">
                    Select an event to start...
                </div>
            </div>
        </div>
    </div>
</div>
<?php require "includes/footer.php"; ?>