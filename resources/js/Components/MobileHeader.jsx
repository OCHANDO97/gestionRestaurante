import { Link } from "@inertiajs/react";

const MobileHeader = ({ title, backRoute }) => {
    
  return (
    <div className="grid grid-cols-3 ml-16 mt-10 gap-8 sm:hidden">
      {/* Botón de regreso */}
      <Link href={backRoute} className="">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          fill="currentColor"
          className="bi bi-arrow-left"
          viewBox="0 0 16 16"
        >
          <path
            fillRule="evenodd"
            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"
          />
        </svg>
      </Link>

      {/* Título central */}
      <h1 className="mt-2 text-center font-semibold text-base sm:text-lg md:text-xl lg:text-2xl">
        {title}
      </h1>
    </div>
  );
};

export default MobileHeader;
