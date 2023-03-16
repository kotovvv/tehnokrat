<!--размер, интерфейс и цвет-->
<script type="text/x-template" id="template16">
    <div v-if="currentVariation.id"
         :class="['items', 'clearfix', (index & 1) ? 'right' : 'left', {'not-available': !currentVariation.in_stock} ]">
        <div class="product-img">
            <img loading="lazy" decoding="async" :src="currentVariation.image" alt="" @click="showGallery">
            <product-label :label="currentVariation.label"></product-label>
        </div>
        <div class="description clearfix">
            <div class="model">
                <ul class="model-item">
                    <more-detailed :url="currentVariation.url"></more-detailed>
                    <li>
                        <h6>конфигурация</h6>
                        <div v-html="currentVariation.description"></div>
                    </li>
                </ul>
            </div>
            <div class="title">
                <h2>{{ product.name }}
                    <span>{{ currentVariation.modification }}</span>
                </h2>
                <more-detailed :url="currentVariation.url"></more-detailed>
                <tehnokrat-color-selector
                        title="ВЫБЕРИТЕ ЦВЕТ КОРПУСА"
                        attribute="color"
                        :items="getSortedAttribute( 'color' )"
                        :availableItems="availableAttributes.color"
                        :value="currentVariation.attributes.color"
                ></tehnokrat-color-selector>
                <tehnokrat-selector
                        title="ВЫБЕРИТЕ РАЗМЕР"
                        attribute="size"
                        :items="getSortedAttribute( 'size' )"
                        :availableItems="availableAttributes.size"
                        :value="currentVariation.attributes.size"
                ></tehnokrat-selector>
                <tehnokrat-selector
                        title="БЕСПРОВОДНАЯ СВЯЗЬ"
                        attribute="connectivity"
                        :items="getSortedAttribute( 'connectivity' )"
                        :availableItems="availableAttributes.connectivity"
                        :value="currentVariation.attributes.connectivity"
                ></tehnokrat-selector>
                <tehnokrat-color-selector-2
                        title="ВЫБЕРИТЕ ЦВЕТ РЕМЕШКА"
                        attribute="color2"
                        :items="getSortedAttribute( 'color2' )"
                        :availableItems="availableAttributes.color2"
                        :value="currentVariation.attributes.color2"
                ></tehnokrat-color-selector-2>
                <add-to-cart></add-to-cart>
            </div>
        </div>
    </div>
</script>
