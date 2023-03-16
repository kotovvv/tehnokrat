<!--страница одного товара-->
<script type="text/x-template" id="template0">
    <div v-if="currentVariation.id" :class="[ 'product-img', {'not-available':  !currentVariation.in_stock} ]">
        <img loading="lazy" decoding="async" :src="currentVariation.image" alt="" @click="showGallery">
        <product-label :label="currentVariation.label"></product-label>
        <span class="model">{{ currentVariation.model }}</span>
        <p class="title">{{ currentVariation.title1 }}</p>
        <add-to-cart></add-to-cart>
    </div>
</script>
