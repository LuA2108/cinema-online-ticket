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
     * @return array Resultado de la operación de listado de todas las imágenes, incluyendo un array de imágenes agrupadas por película o un mensaje de error si la operación falla
     */
    public function listarTodas()
    {
        // Lista todas las imágenes de todas las películas agrupadas por película_id
        return ["success" => true, "datos" => $this->imagenModelo->listar(), "error" => null];
    }

    /**
     * Lista las imagenes de una película específica
     * @param int $peliculaId
     * @return array Resultado de la operación de listado de imágenes por película, incluyendo un array de imágenes o un mensaje de error si la operación falla 
     */
    public function listarImagenesPorPelicula(int $peliculaId)
    {
        if ($peliculaId <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID de película inválido"];
        }

        return ["success" => true, "datos" => $this->imagenModelo->obtenerPorPelicula($peliculaId), "error" => null];
    }

    /**
     * Obtiene una imagen específica por su ID
     * @param int $imagenId
     * @return array  Resultado de la operación de obtención de imagen, incluyendo los detalles de la imagen o un mensaje de error si la operación falla
     */
    public function obtenerImagen(int $imagenId)
    {
        if ($imagenId <= 0) {
            return ["success" => false, "datos" => null, "error" => "ID de imagen inválido"];
        }

        $imagen = $this->imagenModelo->obtenerImagen($imagenId);

        if (!$imagen) {
            return ["success" => false, "datos" => null, "error" => "Imagen no encontrada"];
        }
        return ["success" => true, "datos" => $imagen, "error" => null];
    }

    /**
     * Agregar imagen a película si la película existe y no hay una imagen duplicada
     * @param int $peliculaId ID de la película a la que se le agregará la imagen
     * @param string $tipo Tipo de imagen (poster, banner, etc.)
     * @param string $url URL o enlace de la imagen
     * @return array Resultado de la operación de creación de imagen
     */
    public function agregar(int $peliculaId, string $tipo, string $url)
    {
        // Validar que la película exista antes de agregar la imagen
        $pelicula = $this->peliculaModelo->peliculaId($peliculaId);

        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        if (empty($tipo) || empty($url)) {
            return ["success" => false, "datos" => null, "error" => "Tipo y URL son obligatorios"];
        }

        $pelicula = $this->peliculaModelo->peliculaId($peliculaId);

        if (!$pelicula) {
            return ["success" => false, "datos" => null, "error" => "La película no existe"];
        }

        // Validar que no exista una imagen con la misma URL para la misma película
        if ($this->imagenModelo->existeUrl($peliculaId, $url)) {
            return ["success" => false, "datos" => null, "error" => "Esta imagen ya existe para esta película"];
        }

        $id = $this->imagenModelo->crear($peliculaId, $tipo, $url);

        if (!$id) {
            return ["success" => false, "datos" => null, "error" => "No se pudo crear la imagen"];
        }

        return ["success" => true, "datos" => $id, "error" => null];
    }

    /**
     * Eliminar imagen de película
     * @param int $id ID de la imagen a eliminar
     * @return array Resultado de la eliminación
     */
    public function eliminar(int $id)
    {
        $imagen = $this->imagenModelo->obtenerImagen($id);

        if (!$imagen) {
            return ["success" => false, "datos" => null, "error" => "Imagen no encontrada"];
        }

        $result = $this->imagenModelo->eliminar($id);
        return ["success" => $result, "datos" => $result, "error" => $result ? null : "No se pudo eliminar la imagen"];
    }
}
