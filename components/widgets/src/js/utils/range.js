function range(products, {minPrice, maxPrice}) {
	return products.filter((prod) => prod.priceUAH >= minPrice && prod.priceUAH <= maxPrice )
 }

 export default range;