<?php require_once("includes/header.php"); ?>

<div class="container mt-3">
    <h3>Logi sisse! </h3>
    <form class="mt-3">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Sisesta email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Sisesta parool">
        </div>
        <button type="submit" class="btn btn-primary">Logi sisse</button>
    </form>
</div>
<?php require_once("includes/footer.php"); ?>