<template>
    <div>
        <h1>Welcome, {{ $page.props.auth.user.name }}</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>
defineProps(["message"]);
</script>
