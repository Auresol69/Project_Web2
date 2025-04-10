<!-- Cập nhật thông tin nhóm quyền -->
<?php
require_once '../connect_db.php';
$db = new connect_db();

if ($_SERVER['REQUEST_METHOD']=="POST"){
    $check_permissions = $_POST['permissions'];
    $powergroupid = $_POST['powergroupid'];
    $db->query("DELETE FROM powergroup_func WHERE powergroupid = ?",[$powergroupid]);

    foreach($check_permissions as $funcid){
        $db->query("INSERT INTO powergroup_func (powergroupid,funcid) VALUES(?,?)",[$powergroupid,$funcid]);
    }

    $db->query("UPDATE powergroup SET last_updated=CURRENT_TIMESTAMP() WHERE powergroupid = ?", [$powergroupid]);

    header(header: "Location: ../index.php?page=phanquyen");
    exit();
}