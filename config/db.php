<?php
// Tu archivo de conexión
$host = getenv('PGHOST');  
$db = getenv('PGDATABASE');    
$user = getenv('PGUSER');  
$pass = getenv('PGPASSWORD');  
$port = getenv('PGPORT') ?: '5432'; 

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta de inserción masiva
    $sql = "INSERT INTO products (nombre, marca, modelo, cantidad, ubicacion) VALUES
    ('Router', 'tplink', 'archer c20', 1, 'Anaquel_B Secc_4'),
    ('Router', 'Steren', 'AX1500', 4, 'Anaquel_B Secc_4'),
    ('Camara', 'Hikvision Domo', 'DC-CD2147G2-SU', 1, 'Anaquel_B Secc_4'),
    ('Camrara WiFI', 'Hikvision Domo', 'DS-2CV2141G2-IDW', 15, 'Anaquel_B Secc_4'),
    ('Camrara WiFI', 'Hikvision Bala', 'DS-CV2041G2-IDW', 13, 'Anaquel_B Secc_4'),
    ('NVR WiFI', 'Hikvision 8Ch', 'DS-71.08NI-K1/W/M', 4, 'Anaquel_B Secc_4'),
    ('Switch', 'Hikvision 4P', 'DS-3E0505P-E/M', 1, 'Anaquel_B Secc_4'),
    ('Montaje', 'Montaje para pared PTZ', 'XGA-B-POLE-PTZ', 3, 'Anaquel_B Secc_3'),
    ('Montaje', 'AccessPRO', 'BZL1200N', 1, 'Anaquel_B Secc_3'),
    ('Gigabib NID', 'Mimosa Tierraf', 'MIMOSA-NID', 4, 'Anaquel_B Secc_3'),
    ('Montaje', 'AccessPRO', 'BZL1200W', 10, 'Anaquel_B Secc_3'),
    ('Cierrapuertas', 'AccessPRO', 'ACCCESSDC 65KG', 7, 'Anaquel_B Secc_3'),
    ('Montaje', 'AccessPRO', 'BZL600N', 12, 'Anaquel_B Secc_3'),
    ('Montaje', 'AccessPRO', 'BU600LED', 1, 'Anaquel_B Secc_3'),
    ('Cerradura', 'SYSCOM', 'EMMAG1500', 3, 'Anaquel_B Secc_2'),
    ('Gabinete', 'Altronix', 'ALTV1224C', 1, 'Anaquel_B Secc_2'),
    ('LED', 'barra de 1m', 'KEMA KEUR', 1, 'Anaquel_B Secc_2'),
    ('Montaje', 'EPCOM', 'EPB64FW', 1, 'Anaquel_B Secc_2'),
    ('Equipo', 'ENFORCER', 'E936S45RRGQ', 1, 'Anaquel_B Secc_2'),
    ('Boton', 'Hikvision', 'DSK7P03', 3, 'Anaquel_B Secc_2'),
    ('Boton', 'AccessPRO', 'SYSB11C', 6, 'Anaquel_B Secc_2'),
    ('Boton', 'AccessPRO', 'ACCESK1LTR', 5, 'Anaquel_B Secc_2'),
    ('Boton', 'AccessPRO', 'ACCESS40', 4, 'Anaquel_B Secc_2'),
    ('Boton', 'CHINO', 'XBSSW01', 2, 'Anaquel_B Secc_2'),
    ('Boton', 'Jasco', 'Smartswitch interruptor', 1, 'Anaquel_B Secc_2'),
    ('Equipo', 'EPCOM POWER ADAPTER', 'RT1640LS', 1, 'Anaquel_B Secc_2'),
    ('Cerradura', 'AccessPRO', 'PROEB500U', 1, 'Anaquel_B Secc_2'),
    ('Montaje', 'HIKVISION', 'DS-1271ZJ-PT10', 1, 'Anaquel_B Secc_2'),
    ('FUENTE', 'EPCOM', 'PL12DC3ABK', 3, 'Anaquel_B Secc_2'),
    ('Cerradura', 'AccessPRO', 'PROEB620', 4, 'Anaquel_B Secc_3'),
    ('BIOMETRICO', 'SUPREMA', 'BEW2-ODPB', 1, 'Anaquel_B Secc_2'),
    ('BIOMETRICO', 'SUPREMA', 'XP2-MDPB', 1, 'Anaquel_B Secc_2'),
    ('CAMARA', 'HIKVISION', 'DS-2CD2T47G2-LSU/SL', 8, 'Anaquel_B Secc_4'),
    ('CAMARA', 'HIKVISION', 'DS-2CD2347G2P-LSU/SL', 1, 'Anaquel_B Secc_4'),
    ('Sensor', 'Ajax', 'FIREPROTECT2', 1, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'DOORPROTECT BLACK', 2, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'DOORPROTECT BLANCO', 4, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'WIRELEST INDOOR SIREN', 2, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'LEASKS PROTECT', 1, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'RELAY', 5, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'GLASSPROTECT', 3, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'BOOTHON BLACK', 1, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'SPACECONTROL', 2, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'MOTION PROTECT CURTAIN', 1, 'Anaquel_A Secc_3'),
    ('Sensor', 'Ajax', 'DORPROTECT PLUS', 2, 'Anaquel_A Secc_3'),
    ('ESTROBO LED', 'EPCOM', 'X13RB', 5, 'Anaquel_C Secc_4'),
    ('ESTROBO LED', 'EPCOM', 'X13W', 7, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1280ZJ-S', 7, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-12722ZJ-110', 17, 'Anaquel_C Secc_4'),
    ('Antena', 'Hikvision', '4Gmodulo', 4, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS1280ZJDM18AX', 7, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1280ZJ-DM18', 3, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1260ZJ', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'TR-JB03-G-IN', 2, 'Anaquel_C Secc_4'),
    ('Bases', 'SYSCOM', 'XGA110A', 3, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1473ZJ-155', 2, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1273ZJ-140', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'SYSCOM', 'XGA9011BL', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'SYSCOM', 'TR-WE45-IN', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-KABH6320-T', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1271ZJ-PT10', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1280ZJ-XS', 1, 'Anaquel_C Secc_4'),
    ('Bases', 'Hikvision', 'DS-1280ZJ-XS', 1, 'Anaquel_C Secc_4')
    ;";

    // Ejecutar la consulta
    $pdo->exec($sql);
    echo "Datos insertados correctamente.";
} catch (PDOException $e) {
    echo "Error al insertar datos: " . $e->getMessage();
}
?>
