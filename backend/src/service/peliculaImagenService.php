<?php

namespace App\Service;

use App\Models\PeliculaImagen;
use App\Models\Pelicula;

/**
 * Clase PeliculaImagenService
 * Gestion de la lógica de negocio relacionada con las imágenes de películas, incluyendo validaciones y operaciones CRUD
 * Utiliza los modelos PeliculaImagen y Pelicula para interactuar con la base de datos y realizar las operaciones necesarias
 * Inyección de dependencias para los modelos necesarios en el servicio
 */
class PeliculaImagenService
{
    private PeliculaImagen $imagenModelo;
    private Pelicula $peliculaModelo;

    /**
     * Constructor de la clase ImagenModelo
     * @param PeliculaImagen $imagenModelo Modelo de imagen de película para realizar operaciones CRUD
     * @param Pelicula $peliculaModelo Modelo de película para validar la existencia de películas al agregar imágenes
     * Inyección de dependencias para los modelos necesarios en el servicio
     */
    public function __construct($imagenModelo, $peliculaModelo)
    {
        $this->imagenModelo = $imagenModelo;
        $this->peliculaModelo = $peliculaModelo;
    }

    /**
     * Lista todas las imagenes
     * @return array
     */
    public function listarTodas()
    {
        // Lista todas las imágenes de todas las películas agrupadas por película_id
        return $this->imagenModelo->listar();
    }

    /**
     * Lista las imagenes de una película específica
     * @param int $peliculaId
     * @return array
     */
    public function listarImagenesPorPelicula(int $peliculaId)
    {
        return $this->imagenModelo->obtenerPorPelicula($peliculaId);
    }

    /**
     * Obtiene una imagen específica por su ID
     * @param int $imagenId
     * @return array|null
     */
    public function obtenerImagen(int $imagenId)
    {
        return $this->imagenModelo->obtenerImagen($imagenId);
    }

    /**
     * Agregar imagen a película si la película existe y no hay una imagen duplicada
     * @param int $peliculaId ID de la película a la que se le agregará la imagen
     * @param string $tipo Tipo de imagen (poster, banner, etc.)
     * @param string $url URL o enlace de la imagen
     * @return int ID de la imagen creada o -1 si hubo un error
     */
    public function agregar(int $peliculaId, string $tipo, string $url)
    {
        // Validar que la película exista antes de agregar la imagen
        $pelicula = $this->peliculaModelo->peliculaId($peliculaId);

        if (!$pelicula) {
            throw new \Exception("La película no existe");
        }

        if (empty($tipo) || empty($url)) {
            throw new \Exception("Tipo y URL son obligatorios");
        }

        // Validar que no exista una imagen con la misma URL para la misma película
        if ($this->imagenModelo->existeUrl($peliculaId, $url)) {
            throw new \Exception("Esta imagen ya existe para esta película");
        }

        $id = $this->imagenModelo->crear($peliculaId, $tipo, $url);

        if (!$id) {
            throw new \Exception("No se pudo crear la imagen");
        }

        return $id;
    }

    /**
     * Eliminar imagen de película
     * @param int $id ID de la imagen a eliminar
     * @return bool True si la imagen se eliminó correctamente, False en caso contrario
     */
    public function eliminar(int $id)
    {
        $imagen = $this->imagenModelo->obtenerImagen($id);

        if (!$imagen) {
            return false;
        }

        return $this->imagenModelo->eliminar($id);
    }
}
