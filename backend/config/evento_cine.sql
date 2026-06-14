SET GLOBAL event_scheduler = ON;

CREATE EVENT finalizar_funciones
ON SCHEDULE EVERY 1 MINUTE
DO
UPDATE funcion
SET estado_id = 3
WHERE estado_id = 2
AND fecha_hora <= NOW();

CREATE EVENT finalizar_programaciones
ON SCHEDULE EVERY 1 MINUTE
DO
UPDATE programacion
SET estado = 0
WHERE estado = 1
AND TIMESTAMP(fecha_fin, hora) <= NOW();