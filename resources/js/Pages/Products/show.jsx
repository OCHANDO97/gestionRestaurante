import React from 'react'

export default function Show({ product }) {
  return (
      <div>
          <h1>Product Details</h1>
          <p><strong>Name:</strong> {product.pro_name}</p>
          <p><strong>Price:</strong> ${product.pro_price}</p>
          <p><strong>Category ID:</strong> {product.pro_categoryID}</p>
      </div>
  );
}

