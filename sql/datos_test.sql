INSERT INTO public.grupo_usuario(
	id,nombre)
	VALUES (1,'USUARIO');

INSERT INTO public.grupo_usuario(
	id,nombre)
	VALUES (2,'SUPERVISOR');

INSERT INTO public.usuario(
	nombre, email, clave, tipo_usuario)
	VALUES ('Enrique Saavedra', 'esaavedra@gmail.com', md5('010203'), 1);
INSERT INTO public.usuario(
	nombre, email, clave, tipo_usuario)
	VALUES ('Enrique Saavedra', 'enrique.saavedra.perez@gmail.com', md5('010203'), 2);