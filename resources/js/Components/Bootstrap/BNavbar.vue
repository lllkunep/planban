<script setup>

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <nav class="navbar navbar-expand-sm bg-body-tertiary">
        <div class="container-sm">
            <Link :href="route('dashboard')">
                <ApplicationLogo
                    width="30" height="24"
                />
            </Link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <slot name="nav-items" />
                </ul>

                <div class="d-flex">
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <slot name="nav-right" />
                    </ul>
                </div>

            </div>
        </div>
    </nav>
</template>
