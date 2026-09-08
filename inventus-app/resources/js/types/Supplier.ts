import type AddressType from "./AddressType";

export default interface Supplier {
    id: number;
    name: string;
    cpf: string | null;
    cnpj: string | null;
    email: string | null;
    phone: string | null;
    address_id?: number | null;
    address?: AddressType;
}