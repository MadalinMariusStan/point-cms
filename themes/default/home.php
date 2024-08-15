<?php theme_include('header'); ?>

<div class="col-xl-10 mx-auto">
    <?php if (has_posts()): ?>
        <div class="mb-5 posts">
            <?php posts(); ?>
            <article>
                <img src="<?php echo article_image(); ?>" class="img-fluid" alt="<?php echo article_title(); ?>"/>
                <h1 class="fw-light">
                    <a class="text-decoration-none text-body" href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>"><?php echo article_title(); ?></a>
                </h1>
                <div class="lead">
                    <?php echo substr(strip_tags(article_description()), 0, 350); ?>
                </div>
                <footer class="fw-light ps-0 text-muted">
                    <?php echo __('site.posed'); ?>
                    <time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time> <?php echo __('site.by'); ?> <?php echo article_author('real_name'); ?>. Reading time: <?php echo readingTime(); ?>.
                </footer>
            </article>
            <?php $i = 0;
            while (posts()): ?>
                <article class="mt-3">
                    <h4 class="fw-light">
                        <a class="text-decoration-none text-body" href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>">
                            <?php echo article_title(); ?>
                        </a>
                    </h4>
                    <footer class="ps-0 fw-light text-muted">
                        <?php echo __('site.posed'); ?>
                        <time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo relative_time(article_time()); ?></time> <?php echo __('site.by'); ?> <?php echo article_author('real_name'); ?>.
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center">
            <h1 class="fw-light text-dark"><?php echo __('site.no_posts_yet'); ?></h1>
            <p><?php echo __('site.writ_something'); ?></p>
            <svg width="50%" height="40%" viewBox="0 0 900 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="transparent" d="M0 0h900v600H0z"/>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M612.852 140.325c37.323 0 67.855 30.299 67.855 67.337 0 37.038-30.532 67.337-67.855 67.337-7.487 0-13.619 6.086-13.619 13.516s6.132 13.515 13.619 13.515h35.506c18.207 0 33.106 14.786 33.106 32.854 0 18.068-14.899 32.853-33.106 32.853-15.747 0-28.632 12.787-28.632 28.414 0 28.511-23.506 51.838-52.237 51.838H260.844c-37.323 0-67.855-30.299-67.855-67.337 0-37.038 30.532-67.337 67.855-67.337h6.563c7.488 0 13.62-6.078 13.62-13.516 0-7.43-6.125-13.515-13.62-13.515h-44.181c-18.208 0-33.106-14.786-33.106-32.854 0-18.046 14.861-32.816 33.037-32.853h35.552c15.747 0 28.632-12.787 28.632-28.414 0-28.511 23.506-51.838 52.237-51.838h273.274z"
                      fill="url(#a)"/>
                <path d="m480.803 287.792-12.285-3.737-53.407-1.572-12.045 3.288 1.572 29.349 27.249 17.386 26.43-.53 16.616-15.14 3.689-15.011 2.181-14.033z"
                      fill="#F8AE9D"/>
                <path d="M326.613 334.991c3.865-7.971 7.57-14.37 10.874-19.422a48.399 48.399 0 0 1 27.874-20.288l43.559-11.804s2.262 43.527 32.221 43.527c29.959 0 35.797-40.817 35.797-40.817l50.407 20.112c1.732.673 13.424 4.715 27.024 33.888 12.959 27.778 18.621 42.004 18.621 42.004l-51.932 14.995-3.239 46.703 1.106 26.672-157.622-.353.882-30.489-.657-46.751-1.973 3.192-52.46-21.042s6.752-13.857 19.518-40.127z"
                      fill="#666AF6"/>
                <path d="M446.231 303.32c-12.784-.236-27.307-25.172-27.307-25.172l-.834-64.054c-.668-18.449 10.808-34.168 31.493-34.254l1.92.086c19.252.919 34.498 14.863 35.013 32.046.487 15.992.779 33.659.153 42.194-1.308 17.667-21.464 14.685-21.464 14.685-.042 0 .055 8.9 0 14.098-.153 11.674-13.841 20.768-18.974 20.371z"
                      fill="#F8AE9D"/>
                <path d="M424.477 217.608c-.352-.168-14.122-4.766-14.429 8.835-.308 13.601 14.107 11.093 14.136 10.702.029-.39.293-19.537.293-19.537zM568.515 383.49l21.122 42.389a30.91 30.91 0 0 1 3.208 15.284c-.802 16.455-14.386 29.398-30.857 29.398l-61.069-.96-2.153-30.186 46.093-11.114-17.466-33.023 41.122-11.788zm-258.742-7.297s-13.103 38.925-27.393 74.305c-.289.705-.609 2.021-.866 2.742-3.224 8.773 6.479 17.321 16.279 17.321l82.58-.144v-26.848l-51.37-10.906 18.604-41.314-37.834-15.156z"
                      fill="#F8AE9D"/>
                <path d="M475.286 442.334c-.016.096-1.893-.497-5.02-.69-3.111-.208-7.522.145-11.916 2.021-4.395 1.877-7.715 4.812-9.703 7.201-2.021 2.39-2.887 4.17-2.967 4.106-.032-.016.16-.465.593-1.235.433-.77 1.123-1.86 2.101-3.095 1.957-2.486 5.293-5.533 9.783-7.442 4.491-1.908 8.998-2.229 12.141-1.908 1.572.144 2.839.4 3.689.625.85.208 1.315.385 1.299.417zm-2.823-6.495c-.032.128-1.94-.818-5.228-.915-3.272-.144-7.875.85-12.173 3.449-4.298 2.614-7.522 5.918-9.799 8.291-1.091 1.155-1.989 2.117-2.695 2.855-.641.674-.994 1.042-1.026 1.01-.032-.016.289-.433.882-1.138a97.352 97.352 0 0 1 2.566-2.967c2.229-2.438 5.437-5.822 9.815-8.485 4.379-2.646 9.11-3.608 12.446-3.367 1.668.096 2.999.4 3.881.705.449.128.77.289.995.385.224.096.336.16.336.177z"
                      fill="#EB996E"/>
                <path d="M363.003 438.645h11.853s26.751-9.671 32.252-8.741c4.956.834 23.095 12.879 26.655 15.268.401.273.69.642.866 1.091a3.885 3.885 0 0 1-2.165 4.94l-.369.16.161.545c.208.674.305 1.428.272 2.166-.128 2.55-3.56 3.319-4.859 1.122l.69 1.219c.272.497.417 1.059.384 1.62l-.064 1.107a2.979 2.979 0 0 1-2.053 2.662 2.943 2.943 0 0 1-3.111-.818c-2.293-2.534-7.265-7.602-10.633-8.163-2.983-.498-7.971-.562-10.986-.546a3.7 3.7 0 0 0-3.448 2.374c1.988.144 15.893 7.393 20.817 9.992 1.524.802 2.662 2.245 2.855 3.961.176 1.54-.594 3.16-3.641 2.438-6.543-1.524-18.299-6.929-18.299-6.929s-6.063-1.732-9.992 1.043c-5.533 3.897-8.067 5.324-12.814 3.753-4.748-1.572-8.244-3.833-8.244-3.833l-9.35-.931c-.032 0-8.869-22.1 3.223-25.5z"
                      fill="#F8AE9D"/>
                <path d="M398.335 443.168c.017.096 1.893-.497 5.02-.69 3.112-.208 7.522.145 11.917 2.021 4.394 1.877 7.714 4.812 9.703 7.201 2.021 2.39 2.887 4.17 2.967 4.106.032-.016-.161-.465-.594-1.235a24.042 24.042 0 0 0-2.101-3.095c-1.956-2.486-5.292-5.533-9.783-7.442-4.491-1.908-8.997-2.229-12.141-1.908a22.978 22.978 0 0 0-3.688.625c-.85.225-1.316.385-1.3.417zm2.823-6.495c.032.128 1.94-.818 5.228-.915 3.272-.144 7.875.85 12.173 3.449 4.298 2.614 7.522 5.918 9.799 8.291 1.091 1.155 1.989 2.117 2.695 2.855.641.674.994 1.042 1.026 1.01.032-.016-.288-.433-.882-1.138a94.577 94.577 0 0 0-2.566-2.967c-2.229-2.438-5.437-5.822-9.815-8.484-4.379-2.647-9.11-3.609-12.446-3.368-1.668.096-2.999.4-3.881.705-.449.129-.77.289-.994.385-.225.096-.337.161-.337.177z"
                      fill="#EB996E"/>
                <path d="M379.042 471.315h124.439a6.59 6.59 0 0 0 6.591-6.319l3.737-85.644c.161-3.753-2.838-6.896-6.591-6.896H375.545c-3.753 0-6.752 3.127-6.607 6.88l3.496 85.66c.144 3.528 3.063 6.319 6.608 6.319z"
                      fill="#31446C"/>
                <path d="M428.807 423.47a12.715 12.715 0 0 0 12.725 12.726 12.715 12.715 0 0 0 12.726-12.726 12.715 12.715 0 0 0-12.726-12.725c-7.036 0-12.725 5.719-12.725 12.725z"
                      fill="#fff"/>
                <path fill="#F8AE9D" d="M429.289 242.388h-11.227v48.114h11.227z"/>
                <path d="M424.882 199.397c2.023 2.61 5.591 3.185 8.53 3.702 3.95.693 5.905 2.138 7.956 6.017 1.298 2.477 2.597 5.191 4.921 6.533 1.889 1.096 1.821-1.698 2.12-3.861a5.26 5.26 0 0 1 .116-.569c.457-1.727 2.296-1.433 2.835.271.492 1.534 1.545 2.832 2.885 3.554.218.118.451.221.683.177.314-.044.56-.339.765-.604.43-.556.857-1.263 1.298-2.01 1.449-2.457 4.192-1.579 5.811.771 1.011 1.46 2.214 2.964 3.868 3.303.533.118 1.203.03 1.449-.486.315-.635-.177-1.269-.095-1.918.068-.604.328-.87.615-1.386 1.126-2.003 3.704-2.075 5.289-.411a58.818 58.818 0 0 0 3.446 3.331c.505.443 1.243.885 1.749.443.274-.236.342-.635.397-1.018.287-1.873.56-3.746.847-5.619.164-1.047.725-2.419 1.682-2.153.464.133.738.619.997 1.062.315.56 5.933 6.946 6.042 6.459.875-3.628 2.666-6.931 3.541-10.559 5.659-23.346-19.452-42.12-38.631-42.223-4.264-.029-8.16.04-12.302 1.028-4.416 1.062-9.077 2.748-13.082 5.122-5.618 3.318-9.87 9.35-8.776 16.547"
                      fill="#31446C"/>
                <path d="M434.968 191.505c2.822 3.099 3.569 7.458 3.832 11.576.387 6.21-.138 12.662-3.099 18.175-.843 1.57-1.466 9.336-2.766 8.907-.415-.134-.346-6.157-1.439-9.403-1.688-5.03-9.462-6.948-12.962-3.769-5.588 5.057-2.31 11.2-.456 15.023 1.107 2.307 2.863 4.896 3.168 7.337.373 3.126.484 14.514-1.619 17.17-1.757 2.226-3.624-8.531-5.215-10.651-11.772-15.787-23.06-55.988-2.96-64.707 3.057-1.328 12.491-2.012 15.355-.322"
                      fill="#31446C"/>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M565.563 201.794h3.743c.905 0 1.634-.736 1.634-1.634v-3.743c0-.905-.736-1.634-1.634-1.634h-3.743a1.64 1.64 0 0 0-1.634 1.634v3.743a1.63 1.63 0 0 0 1.634 1.634zm12.979 0h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.743c0-.905-.736-1.634-1.634-1.634h-3.743a1.64 1.64 0 0 0-1.634 1.634v3.743a1.64 1.64 0 0 0 1.634 1.634zm12.987 0h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.743c0-.905-.736-1.634-1.634-1.634h-3.743a1.64 1.64 0 0 0-1.634 1.634v3.743a1.63 1.63 0 0 0 1.634 1.634zm12.979 0h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.743c0-.905-.736-1.634-1.634-1.634h-3.743c-.905 0-1.642.736-1.642 1.634v3.743a1.652 1.652 0 0 0 1.642 1.634zm12.98 0h3.743c.905 0 1.642-.736 1.642-1.634v-3.743c0-.905-.737-1.634-1.642-1.634h-3.743a1.64 1.64 0 0 0-1.634 1.634v3.743a1.64 1.64 0 0 0 1.634 1.634zm-38.47-14.152h3.743c.897 0 1.634-.737 1.634-1.634v-3.752a1.64 1.64 0 0 0-1.634-1.633h-3.743c-.906 0-1.634.736-1.634 1.633V186a1.632 1.632 0 0 0 1.634 1.642zm12.979 0h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.752a1.64 1.64 0 0 0-1.634-1.633h-3.743a1.64 1.64 0 0 0-1.634 1.633V186a1.632 1.632 0 0 0 1.634 1.642zm12.979 0h3.743c.906 0 1.642-.737 1.642-1.634v-3.752c0-.897-.736-1.633-1.642-1.633h-3.743a1.64 1.64 0 0 0-1.634 1.633V186c0 .905.737 1.642 1.634 1.642zm-13.447 29.357h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.744c0-.905-.736-1.634-1.634-1.634h-3.743a1.64 1.64 0 0 0-1.634 1.634v3.744a1.63 1.63 0 0 0 1.634 1.634zm12.979 0h3.743a1.64 1.64 0 0 0 1.634-1.634v-3.744c0-.905-.736-1.634-1.634-1.634h-3.743c-.905 0-1.642.737-1.642 1.634v3.744a1.652 1.652 0 0 0 1.642 1.634z"
                      fill="#E1E4E5"/>
                <path d="M627.609 289.844c.23-7.193 1.862-14.547 4.736-21.42 37.425 13.127 26.459-2.291 3.816-7.514 6.092-10.194 15.333-18.464 27.425-22.29 14.644-4.628 32 5.911 43.195 21.466-17.609 7.307-13.862 30.56 4.391 6.895 3.655 6.552 6.161 13.608 7.011 20.641 2.138 17.319-5.839 34.317-31.655 41.877-41.608 12.256-59.838-12.554-58.919-39.655zm26.161-8.843c-10.988.321 4.391 12.623 12.621 6.667 5.149-3.757-1.61-6.988-12.621-6.667z"
                      fill="#666AF6"/>
                <path d="M696.574 466.745c1.816-140.224-32.965-228.102-32.965-228.102" stroke="#666AF6" stroke-width="2.5"
                      stroke-miterlimit="10"/>
                <path d="M705.878 298.961c5.748 24.261 12.161 10.744 5.219-5.2 11.287-9.668 27.839-14.707 46.206-9.324 14.828 4.353 21.862 14.845 23.012 26.666-23.747-1.146-29.54 14.02.115 5.612-1.403 26.437-29.333 55.646-64.138 37.662-25.54-13.195-24.827-38.555-10.414-55.416zm34.414 32.347c12.598 3.368 13.218-5.91 2.207-7.193-7.839-.916-14.805 3.826-2.207 7.193z"
                      fill="#666AF6"/>
                <path d="M707.425 422.234c-5.771-88.038 44.62-132.367 44.62-132.367" stroke="#666AF6" stroke-width="2.5"
                      stroke-miterlimit="10"/>
                <path d="M606.726 350.769c-12.23-15.945-12.529-42.038-3.127-49.827 8.897-7.376 20.253-8.27 31.196-5.246-2.782 16.586 10.781 23.871 7.54 2.749 25.494 11.386 44.551 42.244 16.184 57.501-21.609 11.592-37.058 8.729-47.103-.091 36.597-2.337 17.861-12.211-4.69-5.086zm9.563-26.826c-10.667 3.138-9.172 6.483 2.644 7.376 11.793.894 11.126-11.385-2.644-7.376z"
                      fill="#666AF6"/>
                <path d="M676.252 421.431c3.518-87.237-65.356-119.126-65.356-119.126" stroke="#666AF6" stroke-width="2.5"
                      stroke-miterlimit="10"/>
                <path d="M745.998 411.841H637.359l14.963 42.256c3.831 10.814 14.109 18.071 25.632 18.071h27.471c11.525 0 21.801-7.236 25.631-18.07l1.886.667-1.886-.667 14.942-42.257z"
                      fill="#fff" stroke="#E1E4E5" stroke-width="4"/>
                <path d="M761.341 410.361v-6.516H623.366v6.516h137.975z" fill="#E1E4E5" stroke="#E1E4E5" stroke-width="4"/>
                <path d="m209.618 412.698 4.575 56.906h31.659l4.585-56.906h-40.819z" fill="#666AF6"/>
                <path d="M207 407.567h46.54v8.294H207v-8.294zm3.189 0h40.163l-2.345-9.325h-35.463l-2.355 9.325z"
                      fill="#31446C"/>
                <path d="M220.962 441.68a9.306 9.306 0 0 0 9.308 9.308 9.306 9.306 0 0 0 9.308-9.308 9.305 9.305 0 0 0-9.308-9.308 9.306 9.306 0 0 0-9.308 9.308z"
                      fill="#fff"/>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M304.478 228.214v-47.435c0-4.914-3.972-8.894-8.876-8.894H242.35c-4.904 0-8.876 3.98-8.876 8.894v47.435a8.895 8.895 0 0 0 4.567 7.774l26.626 14.821a8.853 8.853 0 0 0 8.622 0l26.627-14.821a8.905 8.905 0 0 0 4.562-7.774z"
                      fill="#E1E4E5"/>
                <path d="M283.734 211.109h-29.518m29.518-13.829h-29.518m29.518 27.26h-29.518" stroke="#fff"
                      stroke-width="2.677" stroke-linecap="round" stroke-linejoin="round"/>
                <rect width="646.936" height="9.013" rx="4.507" transform="matrix(-1 0 0 1 765 469.552)" fill="#E1E4E5"/>
                <rect width="29.178" height="3.399" rx="1.7" transform="scale(-1 1) rotate(-45 311.398 534.12)"
                      fill="#E1E4E5"/>
                <rect width="10.834" height="3.399" rx="1.7" transform="scale(-1 1) rotate(-45 317.087 520.656)"
                      fill="#E1E4E5"/>
                <defs>
                    <linearGradient id="a" x1="425.316" y1="620.484" x2="431.256" y2="-194.429"gradientUnits="userSpaceOnUse"><stop stop-color="#fff"/> <stop offset="1" stop-color="#EEE"/></linearGradient>
                </defs>
            </svg>
        </div>
    <?php endif; ?>
</div>
<?php theme_include('footer'); ?>