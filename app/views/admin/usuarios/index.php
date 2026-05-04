<article>
    <h1>Panel para usuarios</h1>

    <div>
        <table class="tabla_panel">
            <thead>
                <tr>ID</tr>
                <tr>Rol</tr>
                <tr>Nombre</tr>
                <tr>Email</tr>
                <tr>Ciudad</tr>
                <tr>Provincia</tr>
                <tr>Fecha registro</tr>
            </thead>
            <tbody>
                <?php if(!empty($usuarios)): ?>
                    <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?>></td>
                    <td><?= $usuario['rol_id'] ?>></td>
                    <td><?= $usuario['nombre'] ?> ?></td>
                    <td><?= $usuario['email'] ?> ?></td>
                    <td><?= $usuario['ciudad'] ?> ?></td>
                    <td><?= $usuario['provincia'] ?></td>
                    <td><?= $usuario['create_time']?></td>
                </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>No hay usuarios</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</article>
