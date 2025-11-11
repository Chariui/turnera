<div class="card shadow-lg">
  <div class="card-body">
    <h2 class="text-center mb-4 fw-bold text-primary">Gestión de Turnos</h2>
    <div class="row">
      <div class="col-md-6 border-end">
        <h5 class="mb-3">Solicitar un turno</h5>

        <form method="POST" action="index.php?action=sacarTurno" id="formTurno">
          <div class="mb-3">
            <label class="form-label">Nombre y Apellido</label>
            <input 
              type="text" 
              name="nombre" 
              class="form-control" 
              required
              pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+"
              title="Solo se permiten letras y espacios"
              oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')"
              placeholder="Ingrese su nombre y apellido">
          </div>

          <div class="mb-3">
            <label class="form-label">DNI</label>
            <input 
              type="text" 
              name="dni" 
              class="form-control" 
              required 
              pattern="[0-9]+"
              placeholder="Ingrese su documento">
          </div>

          <div class="mb-3">
            <label class="form-label">Especialidad / Servicio</label>
            <select name="servicio" id="servicio" class="form-select" required>
              <option value="">Seleccionar...</option>
              <option value="Cardiología">Cardiología</option>
              <option value="Cirugía">Cirugía</option>
              <option value="Psiquiatría">Psiquiatría</option>
              <option value="Odontología">Odontología</option>
              <option value="Traumatología">Traumatología</option>
              <option value="Clínica general">Clínica general</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Fecha</label>
            <select name="fecha" id="fecha" class="form-select" required>
              <option value="">Seleccionar fecha...</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Hora</label>
            <select name="hora" id="hora" class="form-select" required>
              <option value="">Seleccionar horario...</option>
            </select>
          </div>

          <div class="d-grid">
            <button class="btn btn-success btn-lg">Solicitar turno</button>
          </div>
        </form>

        <!-- 📅 Sección añadida: Fechas de especialidades -->
        <hr class="my-4">

        <div class="text-center">
          <button class="btn btn-outline-info" type="button" id="btnFechas">
            📅 Ver fechas de especialidades
          </button>
        </div>

        <div id="infoFechas" class="mt-3 p-3 border rounded bg-light shadow-sm" style="display: none;">
          <h5 class="text-center text-primary mb-3 fw-bold">Horarios de atención por especialidad</h5>
          <ul class="list-unstyled mb-0 text-start">
            <li><strong> Cardiología:</strong> Lunes, Martes y Jueves de 07:30 a 17:00 hs</li>
            <li><strong> Cirugía:</strong> Todos los días de 08:00 a 20:00 hs</li>
            <li><strong> Psiquiatría:</strong> Lunes, Miércoles y Jueves de 08:00 a 17:00 hs</li>
            <li><strong> Odontología:</strong> Lunes, Martes, Miércoles y Jueves de 08:30 a 18:00 hs</li>
            <li><strong> Traumatología:</strong> Lunes, Miércoles y Viernes de 09:00 a 19:00 hs</li>
            <li><strong> Clínica general:</strong> Todos los días de 07:00 a 20:00 hs</li>
          </ul>
        </div>
        <!-- Fin sección añadida -->

      </div>

      <div class="col-md-6 ps-4">
        <h5 class="mb-3">Consultar / Cancelar</h5>

        <form method="POST" action="index.php?action=consultarTurno" class="mb-3">
          <div class="mb-3">
            <label class="form-label">Código de turno</label>
            <input type="text" name="codigo" class="form-control" placeholder="Ej: TUR-20251015-0001" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary">Consultar</button>
          </div>
        </form>

        <hr>

        <form method="POST" action="index.php?action=cancelarTurno"
              onsubmit="return confirm('¿Está seguro que desea cancelar su turno?');">
          <div class="mb-3">
            <label class="form-label">Cancelar por código</label>
            <input type="text" name="codigo" class="form-control" placeholder="Ej: TUR-20251015-0001" required>
          </div>
          <div class="d-grid">
            <button class="btn btn-danger">Cancelar turno</button>
          </div>
        </form>

        <?php if (isset($_SESSION['user_id'])): ?>
          <hr>
          <div class="mt-4">
            <h5 class="text-center mb-3 text-success">Tus turnos</h5>
            <?php if (!empty($turnos_usuario)): ?>
              <ul class="list-group">
                <?php foreach ($turnos_usuario as $t): ?>
                  <?php if ($t['estado'] !== 'cancelado'): ?>
                    <li class="list-group-item">
                      <strong><?= htmlspecialchars($t['servicio']) ?></strong><br>
                      Fecha: <?= htmlspecialchars($t['fecha']) ?> <?= htmlspecialchars($t['hora']) ?><br>
                      Estado: <?= htmlspecialchars($t['estado']) ?><br>
                      Código: <?= htmlspecialchars($t['codigo']) ?>
                    </li>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p class="text-muted text-center">No tenés turnos registrados.</p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="mt-4 text-center">
          <p class="text-muted mb-1">Si ya tenés cuenta:</p>
          <a href="index.php?action=login" class="btn btn-outline-secondary">Iniciar Sesión</a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
const servicioSelect = document.getElementById('servicio');
const fechaSelect = document.getElementById('fecha');
const horaSelect = document.getElementById('hora');
const btnFechas = document.getElementById('btnFechas');
const infoFechas = document.getElementById('infoFechas');

btnFechas.addEventListener('click', () => {
  if (infoFechas.style.display === 'none') {
    infoFechas.style.display = 'block';
    btnFechas.textContent = '❌ Ocultar fechas de especialidades';
  } else {
    infoFechas.style.display = 'none';
    btnFechas.textContent = '📅 Ver fechas de especialidades';
  }
});

servicioSelect.addEventListener('change', () => {
  const servicio = servicioSelect.value;
  fechaSelect.innerHTML = "<option value=''>Seleccionar fecha...</option>";
  horaSelect.innerHTML = "<option value=''>Seleccionar horario...</option>";

  if (!servicio) return;

  let diasPermitidos = [];
  let horaMin = "07:00";
  let horaMax = "20:00";

  switch (servicio) {
    case "Cardiología":
      diasPermitidos = [1, 2, 4];
      horaMin = "07:30";
      horaMax = "17:00";
      break;
    case "Cirugía":
      diasPermitidos = [0, 1, 2, 3, 4, 5, 6];
      horaMin = "08:00";
      horaMax = "20:00";
      break;
    case "Psiquiatría":
      diasPermitidos = [1, 3, 4];
      horaMin = "08:00";
      horaMax = "17:00";
      break;
    case "Odontología":
      diasPermitidos = [1, 2, 3, 4];
      horaMin = "08:30";
      horaMax = "18:00";
      break;
    case "Traumatología":
      diasPermitidos = [1, 3, 5];
      horaMin = "09:00";
      horaMax = "19:00";
      break;
    case "Clínica general":
      diasPermitidos = [0, 1, 2, 3, 4, 5, 6];
      horaMin = "07:00";
      horaMax = "20:00";
      break;
  }

  generarFechas(diasPermitidos);

  fechaSelect.addEventListener('change', () => {
    generarHorarios(horaMin, horaMax);
  });
});

function generarFechas(diasPermitidos) {
  fechaSelect.innerHTML = "<option value=''>Seleccionar fecha...</option>";
  const hoy = new Date();
  const fin = new Date();
  fin.setMonth(hoy.getMonth() + 1);

  const fechaIter = new Date(hoy);
  while (fechaIter <= fin) {
    const dia = fechaIter.getDay();
    const fechaISO = fechaIter.toISOString().split('T')[0];
    const fechaLegible = fechaIter.toLocaleDateString('es-AR', { weekday: 'long', day: 'numeric', month: 'numeric' });
    const option = document.createElement('option');
    option.value = fechaISO;
    option.textContent = fechaLegible.charAt(0).toUpperCase() + fechaLegible.slice(1);

    if (!diasPermitidos.includes(dia)) {
      option.disabled = true;
      option.style.color = "gray";
    }

    fechaSelect.appendChild(option);
    fechaIter.setDate(fechaIter.getDate() + 1);
  }
}

function generarHorarios(min, max) {
  horaSelect.innerHTML = "";
  let [hMin, mMin] = min.split(":").map(Number);
  let [hMax, mMax] = max.split(":").map(Number);

  const inicio = new Date();
  inicio.setHours(hMin, mMin, 0);
  const fin = new Date();
  fin.setHours(hMax, mMax, 0);

  while (inicio <= fin) {
    const horas = String(inicio.getHours()).padStart(2, "0");
    const minutos = String(inicio.getMinutes()).padStart(2, "0");
    const option = document.createElement("option");
    option.value = `${horas}:${minutos}`;
    option.textContent = `${horas}:${minutos}`;
    horaSelect.appendChild(option);
    inicio.setMinutes(inicio.getMinutes() + 10);
  }
}
</script>