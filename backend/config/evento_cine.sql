SET GLOBAL event_scheduler = ON;

CREATE EVENT finalizar_funciones
ON SCHEDULE EVERY 1 DAY
DO
UPDATE funcion
SET estado_id = 4
WHERE estado_id IN (2)
AND fecha_hora < NOW();