import { useState } from "react";
import { useForm, Link } from "@inertiajs/react";
import axios from "axios";
import Navbar from '@/Components/Navbar';
import CSVUploadCard from "@/Components/CSVUploadCard";
import MobileHeader from "@/Components/MobileHeader";
import Header from "@/Components/Header";


const ImportarExportar = () => {

  const [fileCat, setFileCat] = useState(null);
  const [fileProd, setFileProd] = useState(null);
  const [fileMesa, setFileMesa] = useState(null);
  const [messageCat, setMessageCat] = useState(null);
  const [messageProd, setMessageProd] = useState(null);
  const [messageMesa, setMessageMesa] = useState(null);
  const [errorCat, setErrorCat] = useState(null);
  const [errorProd, setErrorProd] = useState(null);
  const [errorMesa, setErrorMesa] = useState(null);



  const handleFileChangeCat = (e) => {
    setFileCat(e.target.files[0]);
    setMessageCat(null);
    setErrorCat(null);
  };

  const handleFileChangeProd = (e) => {
    setFileProd(e.target.files[0]);
    setMessageProd(null);
    setErrorProd(null);
  };

  const handleFileChangeMesa = (e) => {
    setFileMesa(e.target.files[0]);
    setMessageMesa(null);
    setErrorMesa(null);
  };



  const handleSubmit = async (tipo) => {
    let file = tipo === "productos" 
    ? fileProd 
    : tipo === "categorias"
        ? fileCat
        : fileMesa;

    let setMessage = tipo === "productos" 
    ? setMessageProd 
    : tipo === "categorias"
        ? setMessageCat
        : setMessageMesa;


    let setError = tipo === "productos" 
    ? setErrorProd 
    : tipo === "categorias"
        ? setErrorCat
        : setErrorMesa;

    if (!file) {
      setError("Por favor, selecciona un archivo CSV.");
      return;
    }

    const formData = new FormData();
    formData.append("file", file);

    let url = tipo === "productos" 
      ? "/products/upload" 
      : tipo === "categorias"
          ? "/categories/upload" 
          : "/mesas/upload";
    
    
    
    try {
      const response = await axios.post(url, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
        },
      });

      setMessage(response.data.mensaje);
      setError(null);
      
    } catch (error) {
      console.error("Error al subir el archivo:", error);
      setError(error.response?.data.error || "Error al subir el archivo.");
    }
  };

  const exportCSVCategoria = () => {
    window.location.href = "/export-categorias";
  };

  const exportCSVProducto = () => {
    window.location.href = "/export-productos";
  };

  const exportCSVMesa = () => {
    window.location.href = "/export-mesas";
  };


  return (
    <>
      <Navbar />
      <MobileHeader title="Importaciones" backRoute={route('dashboard')} />
      <Header title="Importar CSV" />

      <div className="flex flex-wrap gap-8 justify-center mt-10">

        <CSVUploadCard
          title="Subir Categorías CSV"
          handleSubmit={handleSubmit}
          handleFileChange={handleFileChangeCat}
          exportCSV={exportCSVCategoria}
          message={messageCat}
          error={errorCat}
          tipo="categorias"
        />

        <CSVUploadCard
          title="Subir Productos CSV"
          handleSubmit={handleSubmit}
          handleFileChange={handleFileChangeProd}
          exportCSV={exportCSVProducto}
          message={messageProd}
          error={errorProd}
          tipo="productos"
        />

        <CSVUploadCard
          title="Subir Mesas CSV"
          handleSubmit={handleSubmit}
          handleFileChange={handleFileChangeMesa}
          exportCSV={exportCSVMesa}
          message={messageMesa}
          error={errorMesa}
          tipo="mesas"
        />

      </div>

    </>

  );
};

export default ImportarExportar;
