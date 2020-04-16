from __future__ import annotations
import dataclasses
from typing import List


@dataclasses.dataclass(frozen=True)
class Number:
    value: int

    def __post_init__(self):
        if self.value < 1 or 13 < self.value:
            err = 'Card number must be between 1 and 13, but %s.' % (self.value)
            raise ValueError(err)

    @classmethod
    def all(cls) -> List[Number]:
        return list(map(lambda x: Number(x), list(range(1, 13 + 1))))
