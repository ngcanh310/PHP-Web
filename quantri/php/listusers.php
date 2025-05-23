<?php
require('../includes/header.php');
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Người dùng</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Phone</th>

                        <th>Address</th>
                        <th>Được tạo ngày</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Phone</th>

                        <th>Address</th>
                        <th>Được tạo ngày</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    require('../../db/conn.php');
                    $sql_str = "SELECT 
    users.id AS pid,
    users.name AS pname,
    users.email AS pemail,
    users.phone AS pphone,
    users.address AS pad,
    users.updated_at AS pstatus
FROM users

";
                    $result = mysqli_query($conn, $sql_str);
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>


                        <tr>
                            <td><?= $row['pid'] ?></td>
                            <td><?= $row['pname'] ?></td>
                            <td><?= $row['pemail'] ?></td>
                            <td><?= $row['pphone'] ?></td>
                            <td><?= $row['pad'] ?></td>
                            <td><?= $row['pstatus'] ?></td>

                            <td>
                                <a class="btn btn-danger" href="deleteuser.php?id=<?= $row['pid'] ?>"
                                    onclick="return confirm('Bạn chắc chắn xóa người dùng này?');">Delete</a>
                            </td>

                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



<?php
require('../includes/footer.php');
?>