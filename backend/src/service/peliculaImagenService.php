<?php

namespace App\Service;

use App\Models\PeliculaImagen;

class PeliculaImagenService
{
    private PeliculaImagen $imagenModelo;

    public function __construct($conn)
    {
        $this->imagenModelo = new PeliculaImagen($conn);
    }

    public function listarPorPelicula(int $peliculaId)
    {
        return $this->imagenModelo->obtenerPorPelicula($peliculaId);
    }

    public function obtenerPorPelicula(int $peliculaId)
    {
        return $this->imagenModelo->obtenerPorPelicula($peliculaId);
    }

    /**
     * Agregar imagen a película
     * @param int $peliculaId ID de la película a la que se le agregará la imagen
     * @param string $tipo Tipo de imagen (poster, banner, etc.)
     * @param string $url URL o enlace de la imagen
     * @return int ID de la imagen creada o -1 si hubo un error
     */
    public function agregar(int $peliculaId, string $tipo, string $url)
    {
        return $this->imagenModelo->crear($peliculaId, $tipo, $url);
    }

    /**
     * Eliminar imagen
     * @param int $id ID de la imagen a eliminar
     * @return bool True si la imagen se eliminó correctamente, False en caso contrario
     */
    public function eliminar(int $id)
    {
        return $this->imagenModelo->eliminar($id);
    }
}
