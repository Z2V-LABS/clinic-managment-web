<?php
require_once '../scripts/session_manager.php';
require_once '../controllers/AppointmentController.php';
$appoinmentController = new AppointmentController();

if ($rol == "Paciente") {
    header("Location: 404.php");
    exit();
}

$articulos_x_pagina = 5;

if (!$_GET) {
    header('location:appointments.php?pagina=1');
}

$iniciar = ($_GET['pagina'] - 1) * $articulos_x_pagina;

$citas = $appoinmentController->obtenerCitas();

$citasPaginadas = $appoinmentController->obtenerCitasPaginadas($iniciar, $articulos_x_pagina);

$n_botones_paginacion = ceil(count($citas) / ($articulos_x_pagina));

if ($_GET['pagina'] > $n_botones_paginacion) {
    header('location:appointments.php?pagina=1');
}

$pacientes = $appoinmentController->obtenerListaPacientes();
$fisioterapeutas = $appoinmentController->obtenerListaFisioterapeutas();

include_once './includes/dashboard.php';
include 'modals/appointments/add_modal.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Citas</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AsignarCita"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
            <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
        </svg>
        Asignar Cita
    </button>
</div>

<?php
// Verificar si hay una alerta de usuario
if (isset($_SESSION['alert'])) {
    $alert_type = $_SESSION['alert']['type'];
    $alert_message = $_SESSION['alert']['message'];
    // Mostrar la alerta
    echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show" role="alert">' . $alert_message . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    // Eliminar la variable de sesión después de mostrar la alerta
    unset($_SESSION['alert']);
}
?>

<div class="table-responsive small">
    <form class="row g-3">
        <div class="col-auto">
            <label for="inputPassword2" class="visually-hidden">Filtro</label>
            <input type="text" class="form-control" id="inputPassword2" placeholder="Filtrar por ID de paciente...">
        </div>
        <div class="col-auto">
            <input type="date" class="form-control" id="">
        </div>
        <div class="col-auto">
            <select class="form-select" id="estado" name="estado">
                <option selected>Selecciona un estado</option>
                <option value="programada">Programada</option>
                <option value="realizada">Realizada</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Filtrar</button>
        </div>
    </form>

    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 5%;">ID Pac.</th>
                            <th scope="col" style="width: 10%;">Fecha</th>
                            <th scope="col" style="width: 10%;">Paciente</th>
                            <th scope="col" style="width: 10%;">Contacto Pac.</th>
                            <th scope="col" style="width: 10%;">Fisioterapeuta asociado</th>
                            <!-- <th scope="col" style="width: 10%;">Consulta</th> -->
                            <th scope="col" style="width: 5%;">Estado</th>
                            <th scope="col" style="width: 5%;">Acciones</th>
                        </tr>
                    </thead>
                    <?php foreach ($citasPaginadas as $cita) : ?>
                        <tr>
                            <td><?php echo $cita['paciente_id']; ?></td>
                            <td><?php echo $cita['fecha_hora']; ?></td>
                            <td><?php echo $cita['paciente_nombre'] . " " . $cita['paciente_apellidos']; ?></td>
                            <td><?php echo $cita['paciente_telefono'] ?></td>
                            <td><?php echo $cita['fisioterapeuta_nombre'] . " " . $cita['fisioterapeuta_apellidos'];  ?></td>
                            <td>
                                <?php
                                $estado = $cita['estado'];

                                switch ($estado) {
                                    case 'Realizada':
                                        $text_gb_class = 'text-bg-success';
                                        break;
                                    case 'Cancelada':
                                        $text_gb_class = 'text-bg-danger';
                                        break;
                                    case 'Programada':
                                        $text_gb_class = 'text-bg-warning';
                                        break;
                                    default:
                                        $text_gb_class = 'text-bg-warning';
                                }
                                ?>
                                <span class="badge <?php echo $text_gb_class; ?>">
                                    <?php echo $estado; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-success" <?php if ($cita['estado'] == 'Realizada') {echo 'disabled';} ?> data-bs-toggle="modal" data-bs-target="#confirm_<?php echo $cita['cita_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z" />
                                    </svg></button>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $cita['cita_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg></button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete_<?php echo $cita['cita_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg></button>
                                <?php include 'modals/appointments/edit_delete_modal.php'; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-start">
        <li class="page-item <? echo $_GET['pagina'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="appointments.php?pagina=<?php echo $_GET['pagina'] - 1 ?>">Anterior</a>
        </li>
        <?php for ($i = 0; $i < $n_botones_paginacion; $i++) : ?>
            <li class="page-item <? echo $_GET['pagina'] == $i + 1 ? 'active' : '' ?>"><a class="page-link" href="appointments.php?pagina=<?php echo $i + 1 ?>"><?php echo $i + 1 ?></a></li>
        <?php endfor ?>
        <li class="page-item <? echo $_GET['pagina'] >= $n_botones_paginacion ? 'disabled' : '' ?>">
            <a class="page-link" href="appointments.php?pagina=<?php echo $_GET['pagina'] + 1 ?>">Siguiente</a>
        </li>
    </ul>
</nav>

</main>

</body>

</html>