from __future__ import annotations
import dataclasses
from typing import List

from trump.card import Card
from trump.mark import Mark
from trump.number import Number


@dataclasses.dataclass()
class Deck:
    cards: List[Card]

    @classmethod
    def create(cls) -> Deck:
        cards = []
        for mark in Mark:
            for num in Number.all():
                cards.append(Card(mark, num))
        return Deck(cards)
