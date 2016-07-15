USE netsuite
drop table IF EXISTS factura;
CREATE TABLE factura(
    UUID char(36),
    rfc char(13),
    nombre varchar(100),
    serie varchar(10),
    folio varchar(10),
    fecha datetime,
    subtotal decimal(7,2),
    descuento decimal(7,2),
    total decimal(7,2),
    metododepago varchar(30),
    impuesto varchar(30),
    tasa decimal(5,2),
    importe decimal(7,2),
    pdffilename varchar(50),
    pdfcontenido longblob,
    xmlfilename varchar(50),
    xmlcontenido longblob,
    PRIMARY KEY (UUID));
drop table IF EXISTS detalle;
CREATE TABLE detalle(
    id int NOT NULL AUTO_INCREMENT,
    UUID char(36),
    cantidad int,
    unidad varchar(15),
    noidentificacion varchar(10),
    descripcion varchar(100),
    valorunitario decimal(7,2),
    importe decimal(7,2),
    PRIMARY KEY (id));
