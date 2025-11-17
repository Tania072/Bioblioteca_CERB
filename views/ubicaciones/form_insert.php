<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
?>

<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Nombre: </b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese nombre de la ubicación" name="nombre" required>
</div>

<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Descripción: </b>
    </span>
    <textarea class="form-control" placeholder="Ingrese descripción de la ubicación" name="descripcion" rows="3"></textarea>
</div>

<div class="input-group mb-3">
    <label class="input-group-text" for="estado">
        <b>Estado: </b>
    </label>
    <select class="form-select" id="estado" name="estado">
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
    </select>
</div>