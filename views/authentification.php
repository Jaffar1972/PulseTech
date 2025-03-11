<div class="container pt-5">
<form action="/loginPost" method="post">



<?php if (isset($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="form-group">
 
    <input type="email" class="form-control w-25 mx-auto" name="email" id="email" aria-describedby="emailHelp" autocomplete="email" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
</div>

<div class="form-group">

   
    <input type="password" class="form-control w-25 mx-auto" name="password" id="password" autocomplete="current-password" placeholder="Password">
</div>
</br></br>

<!-- CENTRAGE DE LA CASE À COCHER ET DU BOUTON -->
<div class="text-center">
    <div class="form-check d-inline-block">
        <input type="checkbox" class="form-check-input" id="check">
        <label class="form-check-label" for="check">Check me out</label>
    </div>
    <br> <!-- Pour un petit espace entre la case à cocher et le bouton -->
    <button type="submit" class="btn btn-primary mt-2">Se connecter</button>
</div>

</form>

</div>


