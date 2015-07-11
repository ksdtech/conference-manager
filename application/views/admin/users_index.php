<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
  <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
    <div class="mdl-card__title">
      <h2 class="mdl-card__title-text">All Users</h2>
    </div>
    <div class="mdl-card__supporting-text">
      <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp">
        <thead>
          <tr>
            <th>Level</th>
            <th class="mdl-data-table__cell--non-numeric">First name</th>
            <th class="mdl-data-table__cell--non-numeric">Last name</th>
            <th class="mdl-data-table__cell--non-numeric">Email</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user) { ?>
          <tr>
            <td><?php echo $user['user_level']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['first_name']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['last_name']; ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $user['user_email']; ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="mdl-card__actions mdl-card--border">
      <a href="<?php echo site_url('admin').'/users/add'; ?>" 
        class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
        Add a new user
      </a>
    </div>
  </div>
</section>
