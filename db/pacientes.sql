SELECT 
    td.detalle_corto AS tipodoc,
    p.nrodoc,
    p.nombre,
    p.apellido,
    p.sexo,
    p.fecha_nacimiento,
    vp.descripcion AS pais,
    vpr.descripcion AS provincia,
    vpa.descripcion AS partido,
    vloc.descripcion AS localidad,
    p.codigo_postal,
    p.calle_nombre AS calle,
    p.calle_numero AS nro_calle,
    p.piso AS piso,
    p.departamento AS dpto,
    p.telefono,
    p.telefono2,
    p.email,
    IF(es_donante = '1', 'SI', 'NO') AS donante,
    IFNULL((SELECT
		o.detalle
	FROM
		paciente p2 LEFT JOIN
		paciente_obra_social po ON(p2.tipodoc=po.tipodoc AND p2.nrodoc=po.nrodoc) LEFT JOIN
		obra_social o ON(o.id=po.idobra_social)
	WHERE
		p2.nrodoc=p.nrodoc AND
		p2.tipodoc=p.tipodoc
	ORDER BY 
		o.fecha_creacion ASC LIMIT 1),'SIN OBRA SOCIAL') as Obra_social
FROM
    paciente p
        LEFT JOIN
    tipo_documento td ON (p.tipodoc = td.id)
        LEFT JOIN
    pais vp ON (p.idpais = vp.idresapro)
        LEFT JOIN
    provincia vpr ON (p.idprovincia = vpr.idresapro
        AND p.idpais = vpr.idresapro_pais)
        LEFT JOIN
    partido vpa ON (p.idpartido = vpa.idresapro
        AND p.idpais = vpa.idresapro_pais
        AND p.idprovincia = vpa.idresapro_provincia)
        LEFT JOIN
    localidad vloc ON (p.idlocalidad = vloc.idresapro
        AND p.idpais = vloc.idresapro_pais
        AND p.idprovincia = vloc.idresapro_provincia
        AND p.idpartido = vloc.idresapro_partido);


##Consulta de turnos Atendidos

SELECT
    ta.detalle as tipo_atencion,
    t.fecha,
    t.hora,
    s.detalle as subespecialidad,
    concat(prof.nombre,' ',prof.apellido) as profesional,
    et.detalle as estado,
    td.detalle_corto AS tipodoc,
    p.nrodoc,
    p.nombre,
    p.apellido,
    p.sexo,
    p.fecha_nacimiento,
    vp.descripcion AS pais,
    vpr.descripcion AS provincia,
    vpa.descripcion AS partido,
    vloc.descripcion AS localidad,
    p.codigo_postal,
    p.calle_nombre AS calle,
    p.calle_numero AS nro_calle,
    p.piso AS piso,
    p.departamento AS dpto,
    p.telefono,
    p.telefono2,
    p.email,
    IF(es_donante = '1', 'SI', 'NO') AS donante,
    IFNULL((SELECT
        o.detalle
    FROM
        paciente p2 LEFT JOIN
        paciente_obra_social po ON(p2.tipodoc=po.tipodoc AND p2.nrodoc=po.nrodoc) LEFT JOIN
        obra_social o ON(o.id=po.idobra_social)
    WHERE
        p2.nrodoc=p.nrodoc AND
        p2.tipodoc=p.tipodoc
    ORDER BY 
        o.fecha_creacion ASC LIMIT 1),'SIN OBRA SOCIAL') as Obra_social
FROM
    turno t 
        LEFT JOIN
    tipo_atencion ta ON(t.idtipo_atencion=ta.id)
        LEFT JOIN
    consultorio c ON (t.idconsultorio=c.id)
        LEFT JOIN
    subespecialidad s ON (c.idsubespecialidad=s.id)
        LEFT JOIN
    profesional prof ON (c.idprofesional=prof.id)
        LEFT JOIN
    estado_turno et ON (t.idestado_turno=et.id)
        LEFT JOIN
    paciente p ON (t.tipodoc=p.tipodoc AND t.nrodoc=p.nrodoc)
        LEFT JOIN
    tipo_documento td ON (p.tipodoc = td.id)
        LEFT JOIN
    pais vp ON (p.idpais = vp.idresapro)
        LEFT JOIN
    provincia vpr ON (p.idprovincia = vpr.idresapro
        AND p.idpais = vpr.idresapro_pais)
        LEFT JOIN
    partido vpa ON (p.idpartido = vpa.idresapro
        AND p.idpais = vpa.idresapro_pais
        AND p.idprovincia = vpa.idresapro_provincia)
        LEFT JOIN
    localidad vloc ON (p.idlocalidad = vloc.idresapro
        AND p.idpais = vloc.idresapro_pais
        AND p.idprovincia = vloc.idresapro_provincia
        AND p.idpartido = vloc.idresapro_partido)
LIMIT 9999999999999999;