import React from 'react';

export default function products({ products }) {
    return (
        <div>
            <h1>products</h1>
            <ul>
                {products.map((product) => (
                    <li key={product.pro_id}>{product.pro_name}</li>
                ))}
            </ul>
        </div>
    );
}