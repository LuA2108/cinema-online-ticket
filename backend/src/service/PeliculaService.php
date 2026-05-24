<?php

namespace App\Service;

use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\PeliculaGenero;
use Exception;

class PeliculaService
{
    private $conn;
    private $peliculaModelo;
    private $peliculaGeneroModelo;
    private $generoModelo;

    /**
     * Constructor con de la clase PeliculaService
     * Inicializa el modelo de Pelicula con la conexión a la base de datos proporcionada
     * @param mysqli $conn Conexión a la base de datos
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->peliculaModelo = new Pelicula($conn);
        $this->generoModelo = new Genero($conn);
        $this->peliculaGeneroModelo = new PeliculaGenero($conn);
    }

    // CONSULTAS

    /**
     * Obtiene todas las películas de la base de datos
     * @return array Un array de películas
     */
    public function listarPeliculas()
    {
        return $this->peliculaModelo->listarPeliculas();
    }

    /**
     * Obtiene todas las películas con detalles completos
     * @return array Un array de películas con detalles completos
     */
    public function listarPeliculasCompletas()
    {
        return $this->peliculaModelo->listarPeliculasCompletas();
    }

    /**
     * Obtiene una película por su ID
     * @param int $id El ID de la película a obtener
     * @return array Un array con los detalles de la película
     */
    public function obtenerPeliculaPorId($id)
    {
        return $this->peliculaModelo->peliculaId($id);
    }

    // CREAR PELÍCULA
    /**
     * Crea una nueva película en la base de datos utilizando los datos proporcionados
     * @param array $pelicula Un array con los datos de la película a crear
     * @param array $generos Un array con los IDs de los géneros asociados a la película
     * @return array Un array con el resultado de la creación de la película
     */
    public function crearPelicula($pelicula, $generos)
    {
        try {
            // INICIAR TRANSACCIÓN
            $this->conn->begin_transaction();

            if (!$pelicula || !$generos) {
                $this->conn->rollback();
                return ["error" => "No se proporcionaron datos para crear la película."];
            }

            // 1. Crear película
            $peliculaId = $this->peliculaModelo->agregarPelicula($pelicula['titulo'], $pelicula['descripcion'], $pelicula['director'], $pelicula['anio'], $pelicula['duracion'], $pelicula['precio'], $pelicula['disponible']);

            // 2. Asociar géneros
            foreach ($generos as $generoId) {
                $this->peliculaGeneroModelo
                    ->agregarGenero($peliculaId, $generoId);
            }

            $this->conn->commit();

            return [
                'success' => true,
                'pelicula_id' => $peliculaId
            ];
        } catch (Exception $e) {
            // ROLLBACK
            $this->conn->rollback();
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    // ACTUALIZAR PELÍCULA

    /**
     * Actualiza una película existente en la base de datos utilizando los datos proporcionados
     * @param int $id El ID de la película a actualizar
     * @param array $pelicula Los datos actualizados de la película
     * @param array $generos Los géneros actualizados de la película
     */
    public function actualizarPelicula($id, $pelicula, $generos)
    {
        try {
            $this->conn->begin_transaction();

            if (!$pelicula) {
                $this->conn->rollback();
                return ["error" => "No se proporcionaron datos para actualizar la película."];
            }

            // 1. Actualizar película
            $this->peliculaModelo->actualizarPelicula(
                $pelicula['titulo'],
                $pelicula['descripcion'],
                $pelicula['director'],
                $pelicula['anio'],
                $pelicula['duracion'],
                $pelicula['precio'],
                $pelicula['disponible'],
                $id
            );

            // 2. Actualizar géneros
            $this->sincronizarGeneros($id, $generos);

            $this->conn->commit();

            return [
                "success" => true,
                "message" => "Película actualizada correctamente"
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                "error" => $e->getMessage()
            ];
        }
    }

    // GÉNEROS

    /**
     * Sincroniza los géneros de una película actualizando las relaciones en la base de datos
     * @param int $peliculaId El ID de la película para la cual se sincronizarán los géneros
     * @param array $nuevosGeneros IDs de los géneros que deben estar asociados a la película
     * @return void No retorna nada, pero actualiza las relaciones en la base de datos
     */
    public function sincronizarGeneros(int $peliculaId, array $nuevosGeneros)
    {
        // géneros actuales en DB
        $actuales = array_column($this->peliculaGeneroModelo->obtenerGenerosDePelicula($peliculaId), 'id');

        // calcular diferencias
        $agregar = array_diff($nuevosGeneros, $actuales);
        $eliminar = array_diff($actuales, $nuevosGeneros);

        // eliminar relaciones
        foreach ($eliminar as $generoId) {
            $this->peliculaGeneroModelo
                ->quitarGenero($peliculaId, $generoId);
        }

        // agregar relaciones nuevas
        foreach ($agregar as $generoId) {

            $this->peliculaGeneroModelo
                ->agregarGenero($peliculaId, $generoId);
        }
    }

    // ESTADO DE PELÍCULA

    public function activarPelicula(int $id): bool
    {
        return $this->peliculaModelo
            ->cambiarEstado($id, true);
    }

    public function desactivarPelicula(int $id): bool
    {
        return $this->peliculaModelo
            ->cambiarEstado($id, false);
    }


}
