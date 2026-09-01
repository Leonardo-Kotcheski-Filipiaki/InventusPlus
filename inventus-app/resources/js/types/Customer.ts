import type Address from "./AddressType";
import type { Person } from "./Person";

export default interface Customer {
    id: number;
    person: Person;
    address?: Address;
}