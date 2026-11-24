<?php include '../includes/header.php'; ?>

<div class="container-fluid px-4">
    <div class="row justify-content-center mt-3">
        <div class="col-lg-10">
            <!-- Tarjeta Principal del Desarrollador -->
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden mb-4">
                <!-- Cabecera con Degradado -->
                <div class="bg-gradient p-5 text-white text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="mb-3">
                        <!-- Imagen de Perfil o Logo -->
                        <img src="../<?= htmlspecialchars($configSistema['imagen_sistema']) ?>" alt="Dev Omar Pinto" class="rounded-circle border border-3 border-white shadow bg-white p-1" width="120" height="120" style="object-fit: cover;">
                    </div>
                    <h2 class="fw-bold mb-1">Omar Antonio Pinto Monzón</h2>
                    <p class="text-white-50 mb-0">Desarrollador de Software & Creador de Soluciones Tecnológicas</p>
                    <span class="badge bg-light text-dark mt-2 px-3 py-1 fw-bold">Alias: Dev Omar Pinto (OP)</span>
                </div>

                <!-- Cuerpo de la Información -->
                <div class="card-body p-4 p-md-5 bg-white">
                    <h4 class="fw-bold text-primary mb-3">👨‍💻 Sobre Mí y mi Desempeño</h4>
                    <p class="text-secondary" style="line-height: 1.8;">
                        Apasionado por la tecnología, la programación y la educación informática. Con formación técnica en el área de sistemas (T.S.U. en Informática) y experiencia en el desarrollo de soluciones informáticas locales, administración de entornos Linux y creación de herramientas educativas y de gestión administrativa. Me dedico a diseñar e implementar sistemas funcionales, limpios y eficientes que optimizan los procesos operativos diarios.
                    </p>

                    <!-- Sección de Pasatiempos y Enfoque Tecnológico -->
                    <div class="p-4 bg-light rounded-4 border-start border-4 border-primary my-4">
                        <h5 class="fw-bold text-dark mb-2">🚀 Pasatiempos y Enfoque de Desarrollo</h5>
                        <p class="text-secondary mb-0" style="line-height: 1.7;">
                            En mi tiempo libre disfruto creando <strong>sistemas de escritorio en Python</strong> utilizando <strong>Tkinter</strong> y bases de datos <strong>SQLite3</strong>, así como robustos <strong>sistemas web</strong> en entornos locales impulsados por <strong>XAMPP, PHP y phpMyAdmin</strong>.
                        </p>
                    </div>

                    <hr class="my-4">

                    <h4 class="fw-bold text-primary mb-4">🌐 Mis Redes y Contacto Directo</h4>
                    <p class="text-muted small mb-4">Conéctate conmigo a través de los siguientes canales oficiales:</p>

                    <!-- Botones de Redes Sociales con Imágenes PNG -->
                    <div class="row g-3">
                        <!-- GitHub -->
                        <div class="col-md-6 col-lg-3">
                            <a href="https://github.com/TU_USUARIO" target="_blank" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2 p-3 rounded-3 shadow-sm text-decoration-none">
                                <img src="../assets/img/github.png" alt="GitHub" width="28" height="28">
                                <span class="fw-bold">GitHub</span>
                            </a>
                        </div>

                        <!-- WhatsApp -->
                        <div class="col-md-6 col-lg-3">
                            <a href="https://wa.me/TU_NUMERO" target="_blank" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center gap-2 p-3 rounded-3 shadow-sm text-decoration-none">
                                <img src="../assets/img/whatsapp.png" alt="WhatsApp" width="28" height="28">
                                <span class="fw-bold">WhatsApp</span>
                            </a>
                        </div>

                        <!-- Facebook -->
                        <div class="col-md-6 col-lg-3">
                            <a href="https://facebook.com/TU_PERFIL" target="_blank" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 p-3 rounded-3 shadow-sm text-decoration-none">
                                <img src="../assets/img/facebook.png" alt="Facebook" width="28" height="28">
                                <span class="fw-bold">Facebook</span>
                            </a>
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="col-md-6 col-lg-3">
                            <a href="mailto:tu-correo@example.com" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 p-3 rounded-3 shadow-sm text-decoration-none">
                                <img src="../assets/img/email.png" alt="Correo" width="28" height="28">
                                <span class="fw-bold">Correo</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>