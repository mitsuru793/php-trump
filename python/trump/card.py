import dataclasses

from trump.mark import Mark
from trump.number import Number


@dataclasses.dataclass(frozen=True)
class Card:
    mark: Mark
    number: Number
