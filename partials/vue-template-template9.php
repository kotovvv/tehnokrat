<!--Apple Watch-->
<script type="text/x-template" id="template9">
    <div v-if="currentVariation.id"
         :class="['items', 'clearfix', (index & 1) ? 'right' : 'left', {'not-available': !currentVariation.in_stock} ]">
        <div class="product-img">
            <img loading="lazy" decoding="async" :src="currentVariation.image" alt="" @click="showGallery">
            <product-label :label="currentVariation.label"></product-label>
        </div>
        <div class="description clearfix">
            <div class="model">
                <ul class="model-item">
                    <models-selector
                            :items="models"
                            :value="currentVariation.model"
                    ></models-selector>
                    <li>
                        <h6>Модель</h6>
                        <span>{{ currentVariation.model }}</span>
                    </li>
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
                <tehnokrat-selector
                        title="ВЫБЕРИТЕ РАЗМЕР"
                        attribute="size"
                        :items="getSortedAttribute( 'size' )"
                        :availableItems="availableAttributes.size"
                        :value="currentVariation.attributes.size"
                ></tehnokrat-selector>
                <tehnokrat-selector
                        title="ВЫБЕРИТЕ РЕМЕШОК"
                        attribute="band"
                        :items="getSortedAttribute( 'band' )"
                        :availableItems="availableAttributes.band"
                        :value="currentVariation.attributes.band"
                ></tehnokrat-selector>
                <add-to-cart></add-to-cart>
            </div>
        </div>
    </div>
</script>
