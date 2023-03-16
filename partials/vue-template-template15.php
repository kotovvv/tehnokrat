<!--описание и цвет-->
<script type="text/x-template" id="template15">
    <div v-if="currentVariation.id"
         :class="['items', 'clearfix', (index & 1) ? 'right' : 'left', {'not-available':  !currentVariation.in_stock} ]">
        <div class="product-img">
            <img loading="lazy" decoding="async" :src="currentVariation.image" alt="" @click="showGallery">
            <product-label :label="currentVariation.label"></product-label>
        </div>
        <div class="description clearfix">
            <div class="title-accses">
                <div class="accses">
                    <h4>{{ product.name }}
                        <span>{{ currentVariation.modification }}</span>
                    </h4>
                    <tehnokrat-color-selector
                            title="ВЫБЕРИТЕ ЦВЕТ"
                            attribute="color"
                            :items="getSortedAttribute( 'color' )"
                            :availableItems="availableAttributes.color"
                            :value="currentVariation.attributes.color"
                    ></tehnokrat-color-selector>
                    <div>
                        <h6>Описание</h6>
                        <p>
                            <span v-html="currentVariation.description"></span><a :href="currentVariation.url">подробнее</a>
                        </p>
                    </div>
                </div>
                <add-to-cart></add-to-cart>
            </div>
        </div>
    </div>
</script>
