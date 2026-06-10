import ModeloSala from "../../models/modeloSala.js";
import TablaSala from "../../view/sala/TablaSala.js";
import ModeloButaca from "../../models/modeloButaca.js";
import renderMapaSala from "../../view/butacas/mapaSala.js";

class SalaControlador {

    constructor() {
        this.modeloSala = new ModeloSala();
        this.modeloButaca = new ModeloButaca();
        this.vista = new TablaSala();
    }

    init() {

        this.cargarSalas();
        document.addEventListener("click", async (e) => {

            if (!e.target.classList.contains("btn-butacas")) {
                return;
            }

            const salaId = Number(e.target.dataset.id);
            const butacas = await this.modeloButaca.obtenerButacasPorSala(salaId);

            renderMapaSala(butacas);

            const modal = new bootstrap.Modal(
                document.getElementById("modalButacas")
            );
            document.getElementById('numeroSala').textContent = salaId;
            modal.show();
        });


        this.cambiarEstadoSala();
        
        this.agregarSala();
    }

    async cargarSalas() {

        try {
            const salas = await this.modeloSala.obtenerSalas();
            this.vista.renderizarTablaSalas(salas);

        } catch (error) {

            console.error(error);

            alert(error.message);
        }
    }

    async agregarSala() {
        document.getElementById('form-sala').addEventListener('submit', async (e) => {
            e.preventDefault();

            const filas = parseInt(e.target.filas.value, 10);
            const butacasPorFila = parseInt(e.target.butacasPorFila.value, 10);
            const datos = {
                filas,
                butacasPorFila
            };

            try {
                await this.modeloSala.agregarSala(datos);
                await this.cargarSalas();

                e.target.reset(); // opcional
            } catch (error) {
                console.error(error);
            }
        });
    }

    async cambiarEstadoSala() {
        document.addEventListener("click", async (e) => {

            const btn = e.target.closest(".btn-estado");
            if (!btn) return;

            const salaId = Number(btn.dataset.id);
            const estadoActual = Number(btn.dataset.estado);
            const nuevoEstado = estadoActual === 1 ? 0 : 1;

            await this.modeloSala.cambiarEstadoSala(salaId, nuevoEstado);

            await this.cargarSalas();
        });
    }


}

export default SalaControlador;