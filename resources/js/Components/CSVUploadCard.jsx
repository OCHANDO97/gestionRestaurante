import React from "react";

const CSVUploadCard = ({ title, handleSubmit, handleFileChange, exportCSV, message, error, tipo }) => {
  return (
    <div className="p-6 bg-white shadow-md rounded-lg max-w-lg mx-auto mt-10">
      <h2 className="text-lg font-semibold mb-4 text-center">{title}</h2>

      {message && <p className="text-green-500 font-semibold">{message}</p>}
      {error && <p className="text-red-500 font-semibold">{error}</p>}

      {/* Input de Archivo */}
      <input type="file" accept=".csv" onChange={handleFileChange} className="block w-full mb-4 border p-2 rounded" />

      {/* Botón para subir */}
      <button 
        onClick={() => handleSubmit(tipo)} 
        className="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition mb-2"
      >
        Subir CSV
      </button>

      {/* Botón para exportar */}
      <button 
        onClick={exportCSV} 
        className="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition"
      >
        Exportar CSV
      </button>
    </div>
  );
};


export default CSVUploadCard;

