<form action="<?= create_url_with_attributes(['action' => 'login']) ?>" method="post">
    <input type="text" name="identifier" placeholder="Username or Emai" placeholder="you+issuesboard@youremaildomain"> <?php // issuesboard-dev@fabian.ternismail.de ?>
    <input type="password" name="password" placeholder="Password">
    <button type="submit" name="submit">Log in</button>
</form>