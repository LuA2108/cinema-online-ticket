<?php

namespace App\Service;

use App\Models\Pelicula;
use App\Models\Genero;
use App\Models\PeliculaGenero;
use App\Models\PeliculaImagen;
use Exception;

class PeliculaService
{
    private $conn;
    private Pelicula $peliculaModelo;
    private PeliculaGenero $peliculaGeneroModelo;
    private PeliculaImagen $pelicula_imagen;
    private Genero $generoModelo;

    /**
     * Constructor con de la clase PeliculaService
     * Inicializa el modelo de Pelicula con la conexión a la base de datos proporcionada
     * @param mysqli $conn Conexión a la base de datos
     * @param Pelicula $peliculaModelo Modelo de película para realizar operaciones CRUD
     * @param PeliculaGenero $peliculaGeneroModelo Modelo de relación película-género
     * @param PeliculaImagen $pelicula_imagen
     * @param Genero $generoModelo Modelo de género para realizar operaciones CRUD relacionadas con géneros de películas
     * 
     */
    public function __construct($peliculaModelo, $peliculaGeneroModelo, $pelicula_imagen, $generoModelo, $conn)
    {
        $this->conn = $conn;
        $this->peliculaModelo = $peliculaModelo;
        $this->pelicula_imagen = $pelicula_imagen;
        $this->generoModelo = $generoModelo;
        $this->peliculaGeneroModelo = $peliculaGeneroModelo;
    }

    // CONSULTAS

    /**
     * Obtiene todas las películas de la base de datos
     * @return array Un array de películas
     */
    public function listarPeliculas()
    {
        return ["success" => true, "datos" => $this->peliculaModelo->listarPeliculas(), "error" => null];
    }

    /**
     * Obtiene todas las películas con detalles completos
     * @return array Un array de películas con detalles completos
     */
    public function listarPeliculasCompletas()
    {
        return ["success" => true, "datos" => $this->peliculaModelo->listarPeliculasCompletas(), "error" => null];
    }

    /**
     * Obtiene una película por su ID
     * @param int $id El ID de la película a obtener
     * @return array Un array con los detalles de la película
     */
    public function obtenerPeliculaPorId($id)
    {
        $pelicula = $this->peliculaModelo->peliculaId($id);

        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "No se encontró la película"];
        }

        $pelicula["generos"] = $this->peliculaGeneroModelo->obtenerGenerosDePelicula($id);

        return ["success" => true, "datos" => $pelicula, "error" => null];
    }

    /**
     * Funcion que devuelve los datos de peliculas y todas sus relaciones
     * @param int $id ID pelicula
     * @return array
     */
    public function obtenerPeliculaCompletaPorId($id)
    {
        $pelicula = $this->peliculaModelo->peliculaId($id);

        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "No encontrada"];
        }

        // géneros (ya lo tienes funcionando)
        $pelicula["generos"] = $this->peliculaGeneroModelo->obtenerGenerosDePelicula($id);

        // si tienes imagenes model, si no, deja vacío
        $pelicula["imagenes"] = $this->pelicula_imagen
            ? $this->pelicula_imagen->obtenerPorPelicula($id)
            : [];

        return ["success" => true, "datos" => $pelicula, "error" => null];
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
                return ["success" => false, "datos" => null, "error" => "No se proporcionaron datos para crear la película."];
            }

            // 1. Crear película
            $peliculaId = $this->peliculaModelo->agregarPelicula(
                $pelicula['titulo'],
                $pelicula['descripcion'],
                $pelicula['director'],
                $pelicula['anio'],
                $pelicula['duracion'],
                $pelicula['disponible'],
                $pelicula['destacado']
            );

            // 2. Asociar géneros
            foreach ($generos as $generoId) {
                //  uso consistente del modelo de relación
                $this->peliculaGeneroModelo->agregarGenero($peliculaId, $generoId);
            }

            $this->conn->commit();

            return [
                "success" => true,
                "datos" => $peliculaId,
                "error" => null
            ];
        } catch (Exception $e) {
            // ROLLBACK
            $this->conn->rollback();
            return [
                "success" => false,
                "datos" => null,
                "error" => $e->getMessage()
            ];
        }
    }

    // ACTUALIZAR PELÍCULA

    /**
     * Actualiza una película existente en la base de datos utilizando los datos proporcionados
     * @param int $id El ID de la película a actualizar
     * @param array $pelicula Los datos actualizados de la película
     * @param array $generos Los géneros actualizados de la película
     * @return array Resultado de la operación de actualización, incluyendo un mensaje de éxito o error según corresponda
     */
    public function actualizarPelicula($id, $pelicula, $generos)
    {
        try {
            $this->conn->begin_transaction();

            if (!$pelicula) {
                $this->conn->rollback();
                return ["success" => false, "datos" => null, "error" => "No se proporcionaron datos de la película."];
            }

            // 1. Actualizar película
            $this->peliculaModelo->actualizarPelicula(
                $pelicula['titulo'],
                $pelicula['descripcion'],
                $pelicula['director'],
                $pelicula['anio'],
                $pelicula['duracion'],
                $pelicula['disponible'],
                $pelicula['destacado'],
                $id
            );

            // 2. Actualizar géneros
            $this->sincronizarGeneros($id, $generos ?? []);

            $this->conn->commit();

            return [
                "success" => true,
                "datos" => true,
                "error" => null
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                "success" => false,
                "datos" => null,
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
        $actuales = array_column(
            $this->peliculaGeneroModelo->obtenerGenerosDePelicula($peliculaId),
            'id'
        );

        // calcular diferencias
        $agregar = array_diff($nuevosGeneros, $actuales);
        $eliminar = array_diff($actuales, $nuevosGeneros);

        // eliminar relaciones
        foreach ($eliminar as $generoId) {
            //  se centraliza en PeliculaGenero
            $this->peliculaGeneroModelo->quitarGenero($peliculaId, $generoId);
        }

        // agregar relaciones nuevas
        foreach ($agregar as $generoId) {
            // se centraliza en PeliculaGenero
            $this->peliculaGeneroModelo->agregarGenero($peliculaId, $generoId);
        }
    }

    // ESTADO DE PELÍCULA

    public function activarPelicula(int $id)
    {
        $resultado = $this->peliculaModelo->cambiarEstado($id, true);
        return ["success" => $resultado, "datos" => $resultado, "error" => $resultado ? null : "No se encontró la película con ID: $id."];
    }

    public function desactivarPelicula(int $id)
    {
        $resultado = $this->peliculaModelo->cambiarEstado($id, false);
        return ["success" => $resultado, "datos" => $resultado, "error" => $resultado ? null : "No se encontró la película con ID: $id."];
    }
}
