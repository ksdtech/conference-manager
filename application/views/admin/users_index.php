<h1>All Users</h1>
<table>
	<tr>
		<th>Level</th>
		<th>First name</th>
		<th>Last name</th>
		<th>Email</th>
		<th>Edit</th>
		<th>Delete</th>
	</tr>
    <?php foreach($users as $user) { ?>
    <tr>
        <td><?php echo $user['user_level']; ?></td>
        <td><?php echo $user['first_name']; ?></td>
        <td><?php echo $user['last_name']; ?></td>
        <td><?php echo $user['user_email']; ?></td>
        <td><a href="<?php echo site_url('admin').'/users/edit/'.$user['user_id']; ?>">Edit</a></td>
        <td><a href="<?php echo site_url('admin').'/users/delete/'.$user['user_id']; ?>">Delete</a></td>
    </tr>
    <?php } ?>
</table>

<div><a href="<?php echo site_url('admin').'/users/add'; ?>">Add a new user</a></div>

<h1>Import Users</h1>
